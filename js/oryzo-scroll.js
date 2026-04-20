/**
 * Oryzo-Inspired Scroll 動畫
 * 用 IntersectionObserver 取代 GSAP ScrollTrigger（零依賴）
 * 搭配 oryzo-inspired.css 的 .oryzo-reveal 系列 class
 *
 * 用法：在 HTML 元素加 class="oryzo-reveal"
 * 變體：oryzo-reveal--left / --right / --scale
 * 延遲：oryzo-reveal--delay-1 到 --delay-4
 */
(function () {
    'use strict';

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); // 只觸發一次
            }
        });
    }, {
        threshold: 0.15,    // 元素 15% 可見時觸發
        rootMargin: '0px 0px -50px 0px'  // 提前 50px 觸發
    });

    // 頁面載入後綁定
    document.addEventListener('DOMContentLoaded', function () {
        var elements = document.querySelectorAll(
            '.oryzo-reveal, .oryzo-reveal--left, .oryzo-reveal--right, .oryzo-reveal--scale'
        );
        elements.forEach(function (el) {
            observer.observe(el);
        });
    });
})();
