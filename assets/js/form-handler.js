/**
 * フォーム送信処理（Ajax）
 * - 資料請求フォーム
 * - お問い合わせフォーム
 */
(function($) {
  'use strict';

  $(document).ready(function() {
    // 資料請求フォーム・お問い合わせフォームを対象
    $('.request-form, .contact-form').on('submit', function(e) {
      e.preventDefault();

      var $form = $(this);
      var $submitBtn = $form.find('button[type="submit"]');
      var originalText = $submitBtn.text();

      // 送信中の状態
      $submitBtn.prop('disabled', true).text('送信中...');

      // フォームデータ収集
      var formData = {
        action: 'submit_form',
        nonce: formHandler.nonce,
        form_type: $form.find('input[name="form_type"]').val() || 'request',
        company: $form.find('input[name="company"]').val(),
        department: $form.find('input[name="department"]').val(),
        lastname: $form.find('input[name="lastname"]').val(),
        firstname: $form.find('input[name="firstname"]').val(),
        email: $form.find('input[name="email"]').val(),
        tel: $form.find('input[name="tel"]').val(),
        event_type: $form.find('input[name="event_type"]:checked').val() || '',
        event_timing: $form.find('select[name="event_timing"]').val(),
        event_size: $form.find('select[name="event_size"]').val(),
        message: $form.find('textarea[name="message"]').val() || '',
        page_title: $form.find('input[name="page_title"]').val() || $form.find('input[name="document_title"]').val() || document.title,
        referer: $form.find('input[name="referer"]').val() || document.referrer,
      };

      $.ajax({
        url: formHandler.ajaxUrl,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            // サンクスページへリダイレクト
            window.location.href = response.data.redirect;
          } else {
            alert(response.data.message || '送信に失敗しました。もう一度お試しください。');
            $submitBtn.prop('disabled', false).text(originalText);
          }
        },
        error: function(xhr, status, error) {
          console.error('Form submission error:', error);
          alert('通信エラーが発生しました。もう一度お試しください。');
          $submitBtn.prop('disabled', false).text(originalText);
        }
      });
    });
  });

})(jQuery);
