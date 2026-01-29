/**
 * フォーム送信処理（Ajax経由でPHP処理）
 * - reCAPTCHA v3 トークン生成
 * - Slack通知（PHP経由）
 * - Pardot連携（PHP経由）
 */
(function($) {
  'use strict';

  $(document).ready(function() {

    // formHandlerが定義されているか確認
    if (typeof formHandler === 'undefined') {
      console.error('formHandler is not defined. Check wp_localize_script.');
      return;
    }

    console.log('Form handler initialized', formHandler);

    // reCAPTCHA設定確認
    var recaptchaEnabled = typeof recaptchaConfig !== 'undefined' && recaptchaConfig.enabled;
    if (recaptchaEnabled) {
      console.log('reCAPTCHA v3 enabled');
    }

    /**
     * reCAPTCHAトークン取得
     * @param {string} action アクション名
     * @returns {Promise<string>} トークン
     */
    function getRecaptchaToken(action) {
      return new Promise(function(resolve) {
        if (!recaptchaEnabled || typeof grecaptcha === 'undefined') {
          console.log('reCAPTCHA not available, skipping token');
          resolve('');
          return;
        }

        grecaptcha.ready(function() {
          grecaptcha.execute(recaptchaConfig.siteKey, { action: action })
            .then(function(token) {
              console.log('reCAPTCHA token generated');
              resolve(token);
            })
            .catch(function(err) {
              console.error('reCAPTCHA error:', err);
              resolve('');
            });
        });
      });
    }

    // 資料請求フォーム・お問い合わせフォーム
    $('.request-form, .contact-form').on('submit', function(e) {
      e.preventDefault();

      var $form = $(this);
      var $submitBtn = $form.find('button[type="submit"]');
      var originalText = $submitBtn.text();

      // 送信中の状態
      $submitBtn.prop('disabled', true).text('送信中...');

      // フォームタイプ判定
      var formType = $form.find('input[name="form_type"]').val() || 'request';

      // reCAPTCHAトークン取得後にフォーム送信
      getRecaptchaToken('submit_form').then(function(recaptchaToken) {

        // フォームデータ収集
        var formData = {
          action: 'submit_form',
          nonce: formHandler.nonce,
          form_type: formType,
          recaptcha_token: recaptchaToken,
          company: $form.find('input[name="company"]').val() || '',
          department: $form.find('input[name="department"]').val() || '',
          lastname: $form.find('input[name="lastname"]').val() || '',
          firstname: $form.find('input[name="firstname"]').val() || '',
          email: $form.find('input[name="email"]').val() || '',
          tel: $form.find('input[name="tel"]').val() || '',
          event_type: $form.find('input[name="event_type"]:checked').val() || '',
          event_timing: $form.find('select[name="event_timing"]').val() || '',
          event_size: $form.find('select[name="event_size"]').val() || '',
          message: $form.find('textarea[name="message"]').val() || '',
          page_title: document.title,
          referer: document.referrer
        };

        console.log('Submitting form data:', formData);
        console.log('Ajax URL:', formHandler.ajaxUrl);

        $.ajax({
          url: formHandler.ajaxUrl,
          type: 'POST',
          data: formData,
          dataType: 'json',
          success: function(response) {
            console.log('Ajax response:', response);

            if (response.success) {
              // サンクスページへリダイレクト
              window.location.href = response.data.redirect;
            } else {
              alert(response.data.message || '送信に失敗しました。');
              $submitBtn.prop('disabled', false).text(originalText);
            }
          },
          error: function(xhr, status, error) {
            console.error('Ajax error:', status, error);
            console.error('Response:', xhr.responseText);

            // エラーでもサンクスページへ（ユーザー体験のため）
            var thanksUrl = (formType === 'contact') ? '/contact-thanks/' : '/resource-thanks/';
            window.location.href = thanksUrl;
          }
        });

      });
    });
  });

})(jQuery);
