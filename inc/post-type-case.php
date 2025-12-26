<?php
/**
 * カスタム投稿タイプ：導入事例（case）
 */

error_log('📘 post-type-case.php loaded');

/**
 * 導入事例カスタム投稿タイプ登録
 */
function create_case_post_type() {

  $labels = array(
    'name'               => '導入事例',
    'singular_name'      => '導入事例',
    'add_new'            => '新規追加',
    'add_new_item'       => '導入事例を追加',
    'edit_item'          => '導入事例を編集',
    'new_item'           => '新規導入事例',
    'view_item'          => '導入事例を見る',
    'search_items'       => '導入事例を検索',
    'not_found'          => '導入事例が見つかりません',
    'not_found_in_trash' => 'ゴミ箱に導入事例はありません',
  );

  $args = array(
    'labels'        => $labels,
    'public'        => true,
    'has_archive'   => true,
    'menu_position' => 5,
    'menu_icon'     => 'dashicons-welcome-write-blog',
    'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
    'rewrite'       => array('slug' => 'case'),
  );

  register_post_type('case', $args);
}
add_action('init', 'create_case_post_type');


/* =========================================================
   企業ロゴ用メタボックス追加
========================================================= */
function case_add_meta_boxes() {
    add_meta_box(
        'case_company_logo',
        '企業ロゴ',
        'case_company_logo_callback',
        'case',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'case_add_meta_boxes');

/**
 * メタボックス表示
 */
function case_company_logo_callback($post) {
    wp_nonce_field('case_company_logo_nonce', 'case_company_logo_nonce_field');

    $logo_id = get_post_meta($post->ID, '_company_logo_id', true);
    $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
    ?>
    <div class="company-logo-uploader">
        <img id="company-logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-width:100%; margin-bottom:10px;" />
        <input type="hidden" name="company_logo_id" id="company_logo_id" value="<?php echo esc_attr($logo_id); ?>" />
        <button type="button" class="button" id="company-logo-upload">画像を選択</button>
        <button type="button" class="button" id="company-logo-remove">削除</button>
    </div>
    <script>
    jQuery(document).ready(function($){
        var frame;
        $('#company-logo-upload').on('click', function(e){
            e.preventDefault();
            if(frame) frame.open();

            frame = wp.media({
                title: '企業ロゴを選択',
                button: { text: '選択' },
                multiple: false
            });

            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                $('#company_logo_id').val(attachment.id);
                $('#company-logo-preview').attr('src', attachment.url);
            });

            frame.open();
        });

        $('#company-logo-remove').on('click', function(e){
            e.preventDefault();
            $('#company_logo_id').val('');
            $('#company-logo-preview').attr('src','');
        });
    });
    </script>
    <?php
}

/**
 * 保存処理
 */
function case_save_meta_boxes($post_id) {
    if (!isset($_POST['case_company_logo_nonce_field'])) return;
    if (!wp_verify_nonce($_POST['case_company_logo_nonce_field'], 'case_company_logo_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['company_logo_id'])) {
        update_post_meta($post_id, '_company_logo_id', intval($_POST['company_logo_id']));
    }
}
add_action('save_post', 'case_save_meta_boxes');
