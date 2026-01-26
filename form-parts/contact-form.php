<?php
/**
 * 共通パーツ：お問い合わせフォーム
 * 資料請求フォーム + お問い合わせ内容（自由記述）
 */

// ページ情報の自動取得
$page_title = get_the_title();
$page_id    = get_the_ID();
?>

<form class="contact-form request-form" method="post" action="#">

  <!-- 自動識別用 hidden -->
  <input type="hidden" name="page_title" value="<?php echo esc_attr($page_title); ?>">
  <input type="hidden" name="page_id" value="<?php echo esc_attr($page_id); ?>">
  <input type="hidden" name="referer" value="<?php echo esc_url($_SERVER['HTTP_REFERER'] ?? ''); ?>">
  <input type="hidden" name="form_type" value="contact">

  <div class="form-row">
    <label for="contact_company">貴社名 <span class="required">*</span></label>
    <input type="text" id="contact_company" name="company" required>
  </div>

  <div class="form-row">
    <label for="contact_department">部署</label>
    <input type="text" id="contact_department" name="department">
  </div>

  <div class="form-row name-row">
    <div>
      <label for="contact_lastname">姓 <span class="required">*</span></label>
      <input type="text" id="contact_lastname" name="lastname" required>
    </div>
    <div>
      <label for="contact_firstname">名 <span class="required">*</span></label>
      <input type="text" id="contact_firstname" name="firstname" required>
    </div>
  </div>

  <div class="form-row">
    <label for="contact_email">メールアドレス <span class="required">*</span></label>
    <input type="email" id="contact_email" name="email" required>
  </div>

  <div class="form-row">
    <label for="contact_tel">電話番号</label>
    <input type="tel" id="contact_tel" name="tel">
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
    <label for="contact_message">お問い合わせ内容</label>
    <textarea id="contact_message" name="message" rows="5" placeholder="ご質問やご要望がございましたらご記入ください"></textarea>
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
