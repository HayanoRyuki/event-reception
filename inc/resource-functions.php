<?php
/**
 * Resource（リソース）投稿タイプ用のカスタムフィールド
 */

// =====================================================
// メタボックス登録
// =====================================================
add_action('add_meta_boxes', function () {
  add_meta_box(
    'resource_meta_box',
    '資料情報',
    'render_resource_meta_box',
    'resource',
    'normal',
    'high'
  );
});

// =====================================================
// メタボックス表示
// =====================================================
function render_resource_meta_box($post) {
  wp_nonce_field('resource_meta_nonce_action', 'resource_meta_nonce');

  $page_count   = get_post_meta($post->ID, 'page_count', true);
  $last_updated = get_post_meta($post->ID, 'last_updated', true);
  $audience     = get_post_meta($post->ID, 'audience', true);
  $summary      = get_post_meta($post->ID, 'summary', true);
  $points       = get_post_meta($post->ID, 'points', true);
  ?>
  <style>
    .resource-meta-table { width: 100%; border-collapse: collapse; }
    .resource-meta-table th { text-align: left; padding: 12px 10px; width: 150px; vertical-align: top; background: #f9f9f9; }
    .resource-meta-table td { padding: 12px 10px; }
    .resource-meta-table input[type="text"],
    .resource-meta-table input[type="number"] { width: 200px; padding: 6px 10px; }
    .resource-meta-table textarea { width: 100%; min-height: 80px; padding: 8px 10px; }
  </style>

  <table class="resource-meta-table">
    <tr>
      <th><label for="page_count">ページ数</label></th>
      <td>
        <input type="number" id="page_count" name="page_count" value="<?php echo esc_attr($page_count); ?>" min="1">
      </td>
    </tr>
    <tr>
      <th><label for="last_updated">最終更新日</label></th>
      <td>
        <input type="text" id="last_updated" name="last_updated" value="<?php echo esc_attr($last_updated); ?>" placeholder="例: 2025年5月1日">
      </td>
    </tr>
    <tr>
      <th><label for="audience">対象者</label></th>
      <td>
        <textarea id="audience" name="audience" placeholder="例: イベント受付の効率化を検討している企業・団体の担当者"><?php echo esc_textarea($audience); ?></textarea>
      </td>
    </tr>
    <tr>
      <th><label for="summary">資料の主な内容</label></th>
      <td>
        <textarea id="summary" name="summary" placeholder="例: サービス概要、よくある課題と解決方法、利用フロー、料金プラン"><?php echo esc_textarea($summary); ?></textarea>
      </td>
    </tr>
    <tr>
      <th><label for="points">おすすめポイント</label></th>
      <td>
        <textarea id="points" name="points" placeholder="例: 月額料金不要の使い切り型、QRコードで受付効率化"><?php echo esc_textarea($points); ?></textarea>
      </td>
    </tr>
  </table>
  <?php
}

// =====================================================
// 保存処理
// =====================================================
add_action('save_post_resource', function ($post_id) {
  // nonceチェック
  if (!isset($_POST['resource_meta_nonce']) ||
      !wp_verify_nonce($_POST['resource_meta_nonce'], 'resource_meta_nonce_action')) {
    return;
  }

  // 自動保存はスキップ
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 権限チェック
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  // 各フィールドを保存
  $fields = ['page_count', 'last_updated', 'audience', 'summary', 'points'];

  foreach ($fields as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
    }
  }
});
