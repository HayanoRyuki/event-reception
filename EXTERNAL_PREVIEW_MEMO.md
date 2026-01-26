# single-case 外部確認機能（14日間限定公開リンク）実装メモ

## 概要
下書き状態の導入事例（case）記事を、ログインなしで外部の人に確認してもらえる機能。
Public Post Previewプラグインの仕組みを参考に実装。

---

## 実装手順

### 1. functions.php に追加

```php
// ============================================
// 外部確認URLの閲覧制御（Public Post Preview方式）
// ============================================

/**
 * pre_get_posts: external_previewトークンがある場合、posts_resultsフィルターを登録
 */
add_action('pre_get_posts', function ($query) {
    if (!$query->is_main_query()) {
        return;
    }

    // external_previewとpreviewパラメータをチェック
    $token = $_GET['external_preview'] ?? '';
    $preview = $_GET['preview'] ?? '';
    if (empty($token) || $preview !== 'true') {
        return;
    }

    $post_type = $query->get('post_type');
    if ($post_type !== 'case') {
        return;
    }

    // キャッシュ無効化とnoindex設定
    if (!headers_sent()) {
        nocache_headers();
        header('X-Robots-Tag: noindex');
    }

    // posts_resultsフィルターを登録
    add_filter('posts_results', 'case_external_preview_set_publish', 10, 2);
});

/**
 * posts_results: トークン検証してOKなら記事のステータスを一時的にpublishに変更
 */
function case_external_preview_set_publish($posts, $query) {
    // フィルターを削除（他のクエリに影響させない）
    remove_filter('posts_results', 'case_external_preview_set_publish', 10);

    if (empty($posts)) {
        return $posts;
    }

    $post = $posts[0];
    $post_id = (int) $post->ID;

    // 公開済みなら正規URLにリダイレクト
    if ($post->post_status === 'publish') {
        wp_safe_redirect(get_permalink($post_id), 301);
        exit;
    }

    // トークン検証
    $token   = $_GET['external_preview'] ?? '';
    $saved   = get_post_meta($post_id, '_external_preview_token', true);
    $expires = intval(get_post_meta($post_id, '_external_preview_expires', true));

    if (empty($token) || empty($saved)) {
        wp_die('プレビューリンクが無効です。', '閲覧不可', ['response' => 404]);
    }

    if (!hash_equals($saved, $token)) {
        wp_die('プレビューリンクが無効です。', '閲覧不可', ['response' => 403]);
    }

    if (time() >= $expires) {
        wp_die('このリンクは有効期限が切れています。', '閲覧不可', ['response' => 403]);
    }

    // ★ 重要：記事のステータスを一時的にpublishに変更
    $posts[0]->post_status = 'publish';

    // コメントとピンバックを無効化
    add_filter('comments_open', '__return_false');
    add_filter('pings_open', '__return_false');

    return $posts;
}
```

---

### 2. case-functions.php（または inc/case-functions.php）に追加

```php
/* =========================================================
 * case：外部確認URL（14日間有効）
 * ========================================================= */

/**
 * 外部確認メタボックス追加
 */
function add_case_external_preview_box() {
  add_meta_box(
    'case_external_preview',
    '外部確認',
    'render_case_external_preview_box',
    'case',
    'side',
    'high'
  );
}
add_action('add_meta_boxes', 'add_case_external_preview_box');

/**
 * メタボックス描画
 */
function render_case_external_preview_box($post) {
  // 既存トークンと有効期限を取得
  $token   = get_post_meta($post->ID, '_external_preview_token', true);
  $expires = get_post_meta($post->ID, '_external_preview_expires', true);

  // 現在時刻が有効期限内かどうか
  $is_valid = $token && $expires && time() < intval($expires);

  // Public Post Preview方式のURL生成
  $url = $is_valid
    ? add_query_arg(
        array(
          'p'                => $post->ID,
          'post_type'        => 'case',
          'preview'          => 'true',
          'external_preview' => $token,
        ),
        home_url('/')
      )
    : '';
  ?>
  <p>
    <button type="button" class="button" id="generate-external-preview">
      外部確認URLをコピー（14日間）
    </button>
  </p>

  <?php if ($is_valid): ?>
    <p style="font-size:12px;color:#555;">
      有効期限：<?php echo date('Y/m/d H:i', intval($expires)); ?>
    </p>
    <input
      type="text"
      readonly
      value="<?php echo esc_url($url); ?>"
      style="width:100%;"
    >
  <?php endif; ?>

  <script>
  jQuery(function($){
    $('#generate-external-preview').on('click', function(){
      $.post(ajaxurl, {
        action: 'generate_case_external_preview',
        post_id: <?php echo (int)$post->ID; ?>,
        nonce: '<?php echo wp_create_nonce('generate_case_external_preview'); ?>'
      }, function(res){
        if (res.success) {
          navigator.clipboard.writeText(res.data.url);
          location.reload();
        }
      });
    });
  });
  </script>
  <?php
}

/**
 * AJAX：外部確認URL生成（14日間有効）
 */
add_action('wp_ajax_generate_case_external_preview', function () {
  if (!wp_verify_nonce($_POST['nonce'], 'generate_case_external_preview')) {
    wp_send_json_error();
  }

  $post_id = intval($_POST['post_id']);

  // 推測困難なトークンを生成
  $token   = wp_generate_password(32, false);

  // 有効期限：14日
  $expires = time() + (14 * DAY_IN_SECONDS);

  update_post_meta($post_id, '_external_preview_token', $token);
  update_post_meta($post_id, '_external_preview_expires', $expires);

  // Public Post Preview方式のURL生成
  $url = add_query_arg(
    array(
      'p'                => $post_id,
      'post_type'        => 'case',
      'preview'          => 'true',
      'external_preview' => $token,
    ),
    home_url('/')
  );

  wp_send_json_success(['url' => $url]);
});
```

---

## 使い方

1. 管理画面で導入事例（case）の編集画面を開く
2. 右サイドバーの「外部確認」メタボックスで「外部確認URLをコピー（14日間）」ボタンをクリック
3. URLがクリップボードにコピーされる
4. そのURLを外部の確認者に共有

---

## URL形式

```
https://example.com/?p=記事ID&post_type=case&preview=true&external_preview=トークン
```

---

## 注意点

- トークンは14日間有効（変更する場合は `14 * DAY_IN_SECONDS` を変更）
- 記事が公開されると、外部確認URLは正規URLにリダイレクトされる
- noindex設定済みなので検索エンジンにインデックスされない
