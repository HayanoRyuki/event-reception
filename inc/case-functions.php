<?php
/* =========================================================
 * case：TOP表示チェックボックス
 * ========================================================= */

/**
 * 管理画面サイドバーに「TOP表示」チェックボックスを追加
 */
function add_case_top_checkbox() {
  add_meta_box(
    'case_top_display',
    'TOP表示設定',
    'render_case_top_checkbox',
    'case',
    'side',
    'high'
  );
}
add_action('add_meta_boxes', 'add_case_top_checkbox');

/**
 * チェックボックス描画
 */
function render_case_top_checkbox($post) {
  $value = get_post_meta($post->ID, '_show_on_top', true);
  wp_nonce_field('save_case_top_checkbox', 'case_top_checkbox_nonce');
  ?>
  <label>
    <input type="checkbox" name="show_on_top" value="1" <?php checked($value, '1'); ?> />
    TOPに表示する
  </label>
  <?php
}

/**
 * チェックボックス保存処理
 */
function save_case_top_checkbox($post_id) {
  if (!isset($_POST['case_top_checkbox_nonce']) ||
      !wp_verify_nonce($_POST['case_top_checkbox_nonce'], 'save_case_top_checkbox')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  if (isset($_POST['show_on_top'])) {
    update_post_meta($post_id, '_show_on_top', '1');
  } else {
    delete_post_meta($post_id, '_show_on_top');
  }
}
add_action('save_post', 'save_case_top_checkbox');


/* =========================================================
 * case：導入企業情報・要約メタボックス
 * ========================================================= */

/**
 * メタボックス描画
 */
function render_case_meta_box($post) {
  $logo_image_id = get_post_meta($post->ID, '_case_logo_image_id', true);
  $company_name  = get_post_meta($post->ID, '_case_company_name', true);
  $industry      = get_post_meta($post->ID, '_case_industry', true);
  $employee_size = get_post_meta($post->ID, '_case_employee_size', true);
  $summary       = get_post_meta($post->ID, '_case_summary', true);
  $summary_lines = $summary ? explode("\n", $summary) : [''];

  $image_url = $logo_image_id ? wp_get_attachment_image_url($logo_image_id, 'medium') : '';

  wp_nonce_field('save_case_meta_fields', 'case_meta_nonce');
  ?>
  <style>
    .image-preview { margin-bottom: 1em; }
    #summary-fields input { margin-bottom: 5px; display: block; width: 100%; }
  </style>

  <!-- ロゴ画像 -->
  <p>
    <label>ロゴ画像：</label><br>
    <div class="image-preview">
      <?php if ($image_url): ?>
        <img src="<?php echo esc_url($image_url); ?>" style="max-width:150px;">
      <?php endif; ?>
    </div>
    <input type="hidden" name="case_logo_image_id" id="case_logo_image_id" value="<?php echo esc_attr($logo_image_id); ?>">
    <button type="button" class="button" id="upload_logo_button">画像を選択</button>
    <button type="button" class="button" id="remove_logo_button">画像を削除</button>
  </p>

  <!-- テキスト情報 -->
  <p><label>企業名：</label><br>
    <input type="text" name="case_company_name" value="<?php echo esc_attr($company_name); ?>" style="width:100%;">
  </p>
  <p><label>業種：</label><br>
    <input type="text" name="case_industry" value="<?php echo esc_attr($industry); ?>" style="width:100%;">
  </p>
  <p><label>従業員数：</label><br>
    <input type="text" name="case_employee_size" value="<?php echo esc_attr($employee_size); ?>" style="width:100%;">
  </p>

  <!-- 要約 -->
  <p><label>この記事の要約：</label></p>
  <div id="summary-fields">
    <?php foreach ($summary_lines as $line): ?>
      <input type="text" name="case_summary_lines[]" value="<?php echo esc_attr($line); ?>">
    <?php endforeach; ?>
  </div>
  <p>
    <button type="button" class="button" id="add-summary-line">＋ 行を追加</button>
  </p>

  <script>
  jQuery(function($){
    let frame;
    $('#upload_logo_button').on('click', function(e){
      e.preventDefault();
      if (frame) return frame.open();
      frame = wp.media({ title:'ロゴ画像を選択', button:{text:'この画像を使う'}, multiple:false });
      frame.on('select', function(){
        const a = frame.state().get('selection').first().toJSON();
        $('#case_logo_image_id').val(a.id);
        $('.image-preview').html('<img src="'+a.url+'" style="max-width:150px;">');
      });
      frame.open();
    });

    $('#remove_logo_button').on('click', function(){
      $('#case_logo_image_id').val('');
      $('.image-preview').html('');
    });

    $('#add-summary-line').on('click', function(){
      $('#summary-fields').append('<input type="text" name="case_summary_lines[]" value="">');
    });
  });
  </script>
  <?php
}

/**
 * メタデータ保存
 */
function save_case_meta_fields($post_id) {
  if (!isset($_POST['case_meta_nonce']) ||
      !wp_verify_nonce($_POST['case_meta_nonce'], 'save_case_meta_fields')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  update_post_meta($post_id, '_case_logo_image_id', intval($_POST['case_logo_image_id']));
  update_post_meta($post_id, '_case_company_name', sanitize_text_field($_POST['case_company_name']));
  update_post_meta($post_id, '_case_industry', sanitize_text_field($_POST['case_industry']));
  update_post_meta($post_id, '_case_employee_size', sanitize_text_field($_POST['case_employee_size']));

  $lines = isset($_POST['case_summary_lines'])
    ? array_map('sanitize_text_field', $_POST['case_summary_lines'])
    : [];
  update_post_meta($post_id, '_case_summary', implode("\n", array_filter($lines)));
}
add_action('save_post', 'save_case_meta_fields');

/**
 * メタボックス登録
 */
function add_case_meta_box() {
  add_meta_box(
    'case_meta_box',
    '導入企業情報・要約',
    'render_case_meta_box',
    'case',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'add_case_meta_box');


/* =========================================================
 * case：画像2枚並びショートコード
 * ========================================================= */

function rcp_case_two_image_shortcode($atts) {
  $atts = shortcode_atts([
    'img1' => '',
    'img2' => '',
    'alt1' => '',
    'alt2' => '',
  ], $atts);

  if (!$atts['img1'] || !$atts['img2']) return '';

  ob_start(); ?>
  <div class="rcp-case-image-two">
    <figure><img src="<?php echo esc_url($atts['img1']); ?>" alt="<?php echo esc_attr($atts['alt1']); ?>"></figure>
    <figure><img src="<?php echo esc_url($atts['img2']); ?>" alt="<?php echo esc_attr($atts['alt2']); ?>"></figure>
  </div>
  <?php
  return ob_get_clean();
}
add_shortcode('case_two_image', 'rcp_case_two_image_shortcode');


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
          // URL再生成後、表示を更新
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
  // home_url + ?p=ID&post_type=case&preview=true&external_preview=token
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
