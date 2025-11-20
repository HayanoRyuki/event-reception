<?php
/**
 * Template Name: お問い合わせ
 * 固定ページ「お問い合わせ」専用テンプレート
 */

wp_enqueue_style(
  'eventlp-page',
  get_template_directory_uri() . '/assets/css/page.css',
  [],
  filemtime(get_template_directory() . '/assets/css/page.css')
);

get_header('');
?>

<main class="l-main page page-contact">
  <div class="container">

    <header class="page-header mb-12">
      <h1 class="page-title">お問い合わせ</h1>
      <p class="page-desc">資料請求や導入に関するご質問など、お気軽にご連絡ください。</p>
    </header>

    <form class="contact-form" method="post" action="#">
      <div class="form-row">
        <label for="company">貴社名</label>
        <input type="text" id="company" name="company" required>
      </div>

      <div class="form-row">
        <label for="department">部署</label>
        <input type="text" id="department" name="department">
      </div>

      <div class="form-row name-row">
        <div>
          <label for="lastname">姓</label>
          <input type="text" id="lastname" name="lastname" required>
        </div>
        <div>
          <label for="firstname">名</label>
          <input type="text" id="firstname" name="firstname" required>
        </div>
      </div>

      <div class="form-row">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-row">
        <label for="tel">電話番号</label>
        <input type="tel" id="tel" name="tel">
      </div>

      <div class="form-row">
        <label>イベントの種類</label>
        <ul class="radio-list">
          <li><label><input type="radio" name="event_type" value="社内イベント"> 社内イベント</label></li>
          <li><label><input type="radio" name="event_type" value="社外の人を招待する自社イベント"> 社外の人を招待する自社イベント</label></li>
          <li><label><input type="radio" name="event_type" value="保護者等を招待する学校イベント"> 保護者等を招待する学校イベント</label></li>
          <li><label><input type="radio" name="event_type" value="招待制の式典"> 招待制の式典</label></li>
          <li><label><input type="radio" name="event_type" value="ビジネス系の交流会・勉強会"> ビジネス系の交流会・勉強会</label></li>
          <li><label><input type="radio" name="event_type" value="趣味の交流会・勉強会"> 趣味の交流会・勉強会</label></li>
          <li><label><input type="radio" name="event_type" value="その他"> その他</label></li>
        </ul>
      </div>

      <div class="form-row">
        <label>イベント開催予定時期</label>
        <select name="event_timing">
          <option value="">選択してください</option>
          <option value="1ヶ月以内">1ヶ月以内</option>
          <option value="3ヶ月以内">3ヶ月以内</option>
          <option value="6ヶ月以内">6ヶ月以内</option>
          <option value="1年以内">1年以内</option>
          <option value="未定">未定</option>
        </select>
      </div>

      <div class="form-row">
        <label>イベント招待人数規模</label>
        <select name="event_size">
          <option value="">選択してください</option>
          <option value="〜100人">〜100人</option>
          <option value="〜300人">〜300人</option>
          <option value="〜1000人">〜1000人</option>
          <option value="1001人以上">1001人以上</option>
          <option value="未定">未定</option>
        </select>
      </div>

      <div class="form-row">
        <label for="message">お問い合わせ内容</label>
        <textarea id="message" name="message" rows="5" placeholder="お問い合わせ内容をご記入ください"></textarea>
      </div>

      <div class="form-row check-row">
        <label>
          <input type="checkbox" name="agree" required>
          （株）RECEPTIONISTの
          <a href="/privacy-policy/" target="_blank" rel="noopener">個人情報の取り扱い</a>
          に同意します。
        </label>
      </div>

      <div class="form-row submit-row">
        <button type="submit" class="c-button">送信する</button>
      </div>
    </form>

  </div>
</main>

<?php get_footer(''); ?>
