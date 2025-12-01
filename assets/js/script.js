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
ヘッダー高さを正確に取得して本文に余白を追加（TOPは無効）
=====================================================*/
document.addEventListener("DOMContentLoaded", function () {
  const path = window.location.pathname;

  // TOPページは除外
  if (path === "/" || path === "/index.php") {
    return;
  }

  const body = document.body;

  // LP除外
  if (
    body.classList.contains("single-resource") ||
    body.classList.contains("page-template-page-contact")
  ) {
    return;
  }

  const header = document.querySelector(".l-header");
  const main = document.querySelector(".l-main");

  if (!header || !main) return;

  const setMainPadding = () => {
    const style = window.getComputedStyle(header);
    const headerHeight =
      header.offsetHeight +
      parseFloat(style.marginTop) +
      parseFloat(style.marginBottom);

    main.style.paddingTop = `${headerHeight}px`;
  };

  setMainPadding();
  window.addEventListener("resize", setMainPadding);

  const observer = new MutationObserver(setMainPadding);
  observer.observe(header, { attributes: true, childList: true, subtree: true });
});


/* ===================================================
ヘッダー（header-ads対応）高さを正確に取得して本文に余白を追加
=====================================================*/
document.addEventListener("DOMContentLoaded", function () {
  const headerAds = document.querySelector(".l-header-ads");
  const main = document.querySelector(".l-main");

  if (headerAds && main) {
    const setMainPadding = () => {
      const headerHeight = headerAds.getBoundingClientRect().height;
      main.style.paddingTop = `${headerHeight}px`;
    };

    setMainPadding();
    window.addEventListener("resize", setMainPadding);

    // ヘッダー内の動的変化にも対応
    const observer = new MutationObserver(setMainPadding);
    observer.observe(headerAds, { attributes: true, childList: true, subtree: true });
  }
});

