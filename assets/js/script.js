/* ===================================================
viewport固定（375px以下は小さくさせない）※TOPページのみ
=====================================================*/
(function () {
  // 現在のページがTOP（front-page）のときだけ有効にする
  if (!document.body.classList.contains('home')) return;

  const viewport = document.querySelector('meta[name="viewport"]');

  function switchViewport() {
    const value = window.outerWidth > 375 ? "width=device-width,initial-scale=1" : "width=375";
    if (viewport.getAttribute("content") !== value) {
      viewport.setAttribute("content", value);
    }
  }
  addEventListener("resize", switchViewport, false);
  switchViewport();
})();
/* ===================================================
スクロール監視
=====================================================*/
const intersectionObserver = new IntersectionObserver(function(entries){
  entries.forEach(function(entry) {
    if(entry.isIntersecting){
      entry.target.classList.add("is-in-view");
    } else {
      entry.target.classList.remove("is-in-view");
    }
  });
});

const inViewItems = document.querySelectorAll(".js-in-view");
inViewItems.forEach(function(inViewItem) {
  intersectionObserver.observe(inViewItem);
});


/* ===================================================
スムーススクロール（ヘッダー分オフセット調整付き）
=====================================================*/
document.addEventListener('DOMContentLoaded', function() {
  const header = document.querySelector('.l-header');
  const headerHeight = header ? header.offsetHeight : 0;
  const links = document.querySelectorAll('a[href^="#"]');

  links.forEach(link => {
    link.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href && href.startsWith('#') && href.length > 1) {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      }
    });
  });
});

/* ===================================================
FAQアコーディオン
=====================================================*/
// アニメーションの時間とイージング
const animTiming = {
	duration: 300,
	easing: "ease-in-out",
};

// アコーディオンを閉じるときのキーフレーム
const closingAnimation = (answer) => [
	{
		height: answer.offsetHeight + "px",
		opacity: 1,
	},
	{
		height: 0,
		opacity: 0,
	},
];

// アコーディオンを開くときのキーフレーム
const openingAnimation = (answer) => [
	{
		height: 0,
		opacity: 0,
 },
 {
		height: answer.offsetHeight + "px",
		opacity: 1,
	},
];


document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".js-details").forEach(function (el) {
			const summary = el.querySelector(".js-summary");
			const answer = el.querySelector(".js-content");
			summary.addEventListener("click", (event) => {
				// デフォルトの挙動を無効化
				event.preventDefault();
				// detailsのopen属性を判定
				if (el.getAttribute("open") !== null) {
					// アコーディオンを閉じるときの処理
					const closingAnim = answer.animate(closingAnimation(answer), animTiming);
					closingAnim.onfinish = () => {
						// アニメーションの完了後にopen属性を取り除く
						el.removeAttribute("open");
					};
				} else {
					// open属性を付与
					el.setAttribute("open", "true");
					// アコーディオンを開くときの処理
					const openingAnim = answer.animate(openingAnimation(answer), animTiming);
				}
			});
		});
	});

/* ===================================================
ハンバーガーメニュー（モバイル用）
=====================================================*/
document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.querySelector(".l-header__hamburger");
  const nav = document.querySelector(".l-header__nav");
  const overlay = document.querySelector(".l-header__overlay");
  const navLinks = document.querySelectorAll(".l-header__nav-list a");

  if (!hamburger || !nav) return;

  // メニューを開く/閉じる
  function toggleMenu() {
    const isOpen = nav.classList.contains("is-open");

    if (isOpen) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  function openMenu() {
    hamburger.classList.add("is-active");
    hamburger.setAttribute("aria-expanded", "true");
    hamburger.setAttribute("aria-label", "メニューを閉じる");
    nav.classList.add("is-open");
  }

  function closeMenu() {
    hamburger.classList.remove("is-active");
    hamburger.setAttribute("aria-expanded", "false");
    hamburger.setAttribute("aria-label", "メニューを開く");
    nav.classList.remove("is-open");
  }

  // ハンバーガーボタンクリック
  hamburger.addEventListener("click", toggleMenu);

  // オーバーレイクリックで閉じる
  if (overlay) {
    overlay.addEventListener("click", closeMenu);
  }

  // ナビリンククリックで閉じる（ページ内リンク用）
  navLinks.forEach(function (link) {
    link.addEventListener("click", function () {
      // 960px以下のときのみ閉じる
      if (window.innerWidth <= 960) {
        closeMenu();
      }
    });
  });

  // ESCキーで閉じる
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && nav.classList.contains("is-open")) {
      closeMenu();
    }
  });

  // リサイズ時にメニューを閉じる（PC幅になったとき）
  window.addEventListener("resize", function () {
    if (window.innerWidth > 960 && nav.classList.contains("is-open")) {
      closeMenu();
    }
  });
});

/* ===================================================
ヘッダー高さを正確に取得して本文に余白を追加（全ページ対応）
=====================================================*/
document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;

  // LP・ヘルプページは除外（CSSで既にpadding-top設定済み）
  if (
    body.classList.contains("single-resource") ||
    body.classList.contains("single-case") ||
    body.classList.contains("single-help") ||
    body.classList.contains("post-type-archive-help") ||
    body.classList.contains("page-template-page-contact")
  ) return;

  const main = document.querySelector(".l-main");
  const header = document.querySelector(".l-header");

  if (!main || !header) return;

  // ヘッダーがfixedの場合のみpadding-topを設定（absoluteの場合は不要）
  const headerPosition = getComputedStyle(header).position;
  if (headerPosition !== "fixed") return;

  const setMainPadding = () => {
    const headerHeight = header.offsetHeight;
    main.style.paddingTop = `${headerHeight}px`;
  };

  setMainPadding();
  window.addEventListener("resize", setMainPadding);

  const observer = new MutationObserver(setMainPadding);
  observer.observe(header, { attributes: true, childList: true, subtree: true });
});
