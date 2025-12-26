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
          const targetPosition =
            target.getBoundingClientRect().top +
            window.pageYOffset -
            headerHeight;

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
const animTiming = {
	duration: 300,
	easing: "ease-in-out",
};

const closingAnimation = (answer) => [
	{ height: answer.offsetHeight + "px", opacity: 1 },
	{ height: 0, opacity: 0 },
];

const openingAnimation = (answer) => [
	{ height: 0, opacity: 0 },
	{ height: answer.offsetHeight + "px", opacity: 1 },
];

document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".js-details").forEach(function (el) {
		const summary = el.querySelector(".js-summary");
		const answer = el.querySelector(".js-content");

		summary.addEventListener("click", (event) => {
			event.preventDefault();

			if (el.getAttribute("open") !== null) {
				const closingAnim = answer.animate(closingAnimation(answer), animTiming);
				closingAnim.onfinish = () => el.removeAttribute("open");
			} else {
				el.setAttribute("open", "true");
				answer.animate(openingAnimation(answer), animTiming);
			}
		});
	});
});


/* ===================================================
ヘッダー高さを正確に取得して本文に余白を追加（全ページ共通）
=====================================================*/
document.addEventListener("DOMContentLoaded", function () {
  const main = document.querySelector(".l-main");
  if (!main) return;

  const header = document.querySelector(".l-header");
  const headerAds = document.querySelector(".l-header-ads");

  const setMainPadding = () => {
    const targetHeader = headerAds || header;
    if (!targetHeader) return;

    // ★ 修正：margin を含めず offsetHeight のみを採用
    const headerHeight = targetHeader.offsetHeight;

    main.style.paddingTop = `${headerHeight}px`;
  };

  // 初期設定
  setMainPadding();

  // リサイズ対応
  window.addEventListener("resize", setMainPadding);

  // ヘッダーのDOM変化も監視
  const observer = new MutationObserver(setMainPadding);
  if (header) observer.observe(header, { attributes: true, childList: true, subtree: true });
  if (headerAds) observer.observe(headerAds, { attributes: true, childList: true, subtree: true });
});
