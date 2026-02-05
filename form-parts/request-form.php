<?php
/**
 * 共通パーツ：資料請求フォーム
 * ページごとに資料タイトル・IDを自動でPOST送信
 */

// ページ情報の自動取得
$document_title = get_the_title();
$document_id    = get_the_ID();
?>

<form class="request-form" method="post" action="#">

  <!-- 自動識別用 hidden -->
  <input type="hidden" name="form_type" value="request">
  <input type="hidden" name="page_title" value="<?php echo esc_attr($document_title); ?>">
  <input type="hidden" name="document_id" value="<?php echo esc_attr($document_id); ?>">
  <input type="hidden" name="referer" value="<?php echo esc_url($_SERVER['HTTP_REFERER'] ?? ''); ?>">

  <!-- Honeypot（スパム対策：ボットは自動入力するが人間は見えない） -->
  <div class="hp-field" aria-hidden="true">
    <label for="website_url">Website</label>
    <input type="text" id="website_url" name="website_url" autocomplete="off" tabindex="-1">
  </div>

  <div class="form-row">
    <label for="company">貴社名 <span class="required">*</span></label>
    <input type="text" id="company" name="company" required>
  </div>

  <div class="form-row">
    <label for="department">部署 <span class="required">*</span></label>
    <input type="text" id="department" name="department" required>
  </div>

  <div class="form-row name-row">
    <div>
      <label for="lastname">姓 <span class="required">*</span></label>
      <input type="text" id="lastname" name="lastname" required>
    </div>
    <div>
      <label for="firstname">名 <span class="required">*</span></label>
      <input type="text" id="firstname" name="firstname" required>
    </div>
  </div>

  <div class="form-row">
    <label for="email">メールアドレス <span class="required">*</span></label>
    <input type="email" id="email" name="email" required>
  </div>

  <div class="form-row">
    <label for="tel">電話番号 <span class="required">*</span></label>
    <input type="tel" id="tel" name="tel" required>
  </div>

  <div class="form-row">
    <label>イベントの種類 <span class="required">*</span></label>
    <ul class="radio-list">
      <li><label><input type="radio" name="event_type" value="社内イベント" required> 社内イベント</label></li>
      <li><label><input type="radio" name="event_type" value="社外の人を招待する自社イベント"> 社外の人を招待する自社イベント</label></li>
      <li><label><input type="radio" name="event_type" value="保護者等を招待する学校イベント"> 保護者等を招待する学校イベント</label></li>
      <li><label><input type="radio" name="event_type" value="招待制の式典"> 招待制の式典</label></li>
      <li><label><input type="radio" name="event_type" value="ビジネス系の交流会・勉強会"> ビジネス系の交流会・勉強会</label></li>
      <li><label><input type="radio" name="event_type" value="趣味の交流会・勉強会"> 趣味の交流会・勉強会</label></li>
      <li><label><input type="radio" name="event_type" value="その他"> その他</label></li>
    </ul>
  </div>

  <div class="form-row">
    <label>イベント開催予定時期 <span class="required">*</span></label>
    <select name="event_timing" required>
      <option value="">選択してください</option>
      <option value="1ヶ月以内">1ヶ月以内</option>
      <option value="3ヶ月以内">3ヶ月以内</option>
      <option value="6ヶ月以内">6ヶ月以内</option>
      <option value="1年以内">1年以内</option>
      <option value="未定">未定</option>
    </select>
  </div>

  <div class="form-row">
    <label>イベント招待人数規模 <span class="required">*</span></label>
    <select name="event_size" required>
      <option value="">選択してください</option>
      <option value="〜100人">〜100人</option>
      <option value="101〜300人">101〜300人</option>
      <option value="301〜1000人">301〜1000人</option>
      <option value="1001人以上">1001人以上</option>
      <option value="未定">未定</option>
    </select>
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
