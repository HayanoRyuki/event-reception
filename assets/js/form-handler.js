/**
 * フォーム送信処理
 * - Slack通知（Webhook直接呼び出し）
 * - Pardotへリダイレクト
 */
(function($) {
  'use strict';

  // Slack Webhook URLs
  var SLACK_WEBHOOKS = {
    request: 'https://hooks.slack.com/services/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',
    contact: 'https://hooks.slack.com/services/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'
  };

  // Pardot Form Handler URLs
  var PARDOT_ENDPOINTS = {
    request: 'https://t.receptionist.jp/l/436112/2026-01-25/8m98g4',
    contact: 'https://t.receptionist.jp/l/436112/2026-01-26/8m98gm'
  };

  // Thanks Page URLs
  var THANKS_URLS = {
    request: '/resource-thanks/',
    contact: '/contact-thanks/'
  };

  $(document).ready(function() {
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

      // フォームデータ収集
      var formData = {
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
        page_title: document.title
      };

      // 1. Slack通知を送信
      sendSlackNotification(formType, formData);

      // 2. Pardotへ送信（非同期）
      sendToPardot(formType, formData);

      // 3. サンクスページへリダイレクト（少し待ってから）
      setTimeout(function() {
        window.location.href = THANKS_URLS[formType];
      }, 500);
    });
  });

  /**
   * Slack通知送信
   */
  function sendSlackNotification(formType, data) {
    var webhookUrl = SLACK_WEBHOOKS[formType];
    if (!webhookUrl) return;

    var title = (formType === 'contact') ? ':email: お問い合わせ' : ':page_facing_up: 資料請求';
    var now = new Date();
    var timestamp = now.getFullYear() + '/' +
                    ('0' + (now.getMonth() + 1)).slice(-2) + '/' +
                    ('0' + now.getDate()).slice(-2) + ' ' +
                    ('0' + now.getHours()).slice(-2) + ':' +
                    ('0' + now.getMinutes()).slice(-2);

    var blocks = [
      {
        type: 'header',
        text: {
          type: 'plain_text',
          text: title + ' - ' + data.company,
          emoji: true
        }
      },
      {
        type: 'section',
        fields: [
          { type: 'mrkdwn', text: '*会社名:*\n' + data.company },
          { type: 'mrkdwn', text: '*部署:*\n' + (data.department || '-') }
        ]
      },
      {
        type: 'section',
        fields: [
          { type: 'mrkdwn', text: '*お名前:*\n' + data.lastname + ' ' + data.firstname },
          { type: 'mrkdwn', text: '*メール:*\n' + data.email }
        ]
      },
      {
        type: 'section',
        fields: [
          { type: 'mrkdwn', text: '*電話番号:*\n' + (data.tel || '-') },
          { type: 'mrkdwn', text: '*イベント種類:*\n' + (data.event_type || '-') }
        ]
      },
      {
        type: 'section',
        fields: [
          { type: 'mrkdwn', text: '*開催予定時期:*\n' + (data.event_timing || '-') },
          { type: 'mrkdwn', text: '*招待人数規模:*\n' + (data.event_size || '-') }
        ]
      }
    ];

    // お問い合わせ内容がある場合
    if (data.message) {
      blocks.push({
        type: 'section',
        text: { type: 'mrkdwn', text: '*お問い合わせ内容:*\n' + data.message }
      });
    }

    // タイムスタンプ
    blocks.push({
      type: 'context',
      elements: [
        { type: 'mrkdwn', text: ':link: 送信元: ' + data.page_title },
        { type: 'mrkdwn', text: ':clock1: ' + timestamp }
      ]
    });

    var payload = {
      blocks: blocks,
      text: title + ' - ' + data.company
    };

    // Slack Webhookへ送信（navigator.sendBeacon使用）
    if (navigator.sendBeacon) {
      navigator.sendBeacon(webhookUrl, JSON.stringify(payload));
    } else {
      // フォールバック: XMLHttpRequest
      var xhr = new XMLHttpRequest();
      xhr.open('POST', webhookUrl, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(payload));
    }
  }

  /**
   * Pardotへ送信
   */
  function sendToPardot(formType, data) {
    var endpoint = PARDOT_ENDPOINTS[formType];
    if (!endpoint) return;

    // Pardotフィールドマッピング
    var pardotData = {
      email: data.email,
      company: data.company,
      department: data.department,
      last_name: data.lastname,
      first_name: data.firstname,
      phone: data.tel,
      event_type: data.event_type,
      event_timing: data.event_timing,
      event_size: data.event_size,
      comments: data.message
    };

    // URLエンコードされたフォームデータを作成
    var formBody = Object.keys(pardotData)
      .map(function(key) {
        return encodeURIComponent(key) + '=' + encodeURIComponent(pardotData[key] || '');
      })
      .join('&');

    // Pardotへ送信（非同期）
    if (navigator.sendBeacon) {
      var blob = new Blob([formBody], { type: 'application/x-www-form-urlencoded' });
      navigator.sendBeacon(endpoint, blob);
    } else {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', endpoint, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send(formBody);
    }
  }

})(jQuery);
