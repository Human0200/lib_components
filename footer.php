<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
?>
<div class="popup-overlay" id="popupOverlay"></div>

<!-- Картинка слева снизу - ВНЕ контейнера -->
<img src="/upload/bigcube.png" alt="" class="thank-you-decor-bottom" id="thankYouDecorBottom">
<button class="close-popup" id="closePopupBtn">&times;</button>
<div class="thank-you-container" id="thankYouPopup">


    <!-- Картинка справа сверху - внутри контейнера -->
    <img src="/upload/smallcube.png" alt="" class="thank-you-decor thank-you-decor-top">

    <div class="thank-you-content">
        <h3>Спасибо за заявку!</h3>
        <p>Менеджер скоро свяжется с вами!</p>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="footer__desc">
            <a class="footer__logo" href="<?= SITE_DIR ?>">
                <svg width="273" height="53" viewBox="0 0 273 53" fill="none" xmlns="http://www.w3.org/2000/svg" style="translate: none; rotate: none; scale: none; opacity: 1; transform: translate(0px);">
                    <g clip-path="url(#clip0_29_1308)">
                        <path d="M27.21 10.12L9.67 0L0 5.59L27.21 21.3L54.42 5.59L44.75 0L27.21 10.12Z" fill="currentColor"></path>
                        <path d="M12.77 18.15V27.71L27.21 36.05V26.49L12.77 18.15Z" fill="currentColor"></path>
                        <path d="M27.21 41.54L9.67 31.42L0 37.01L27.21 52.72L54.42 37.01L44.75 31.42L27.21 41.54Z" fill="currentColor"></path>
                        <path d="M54.42 5.59L44.72 11.19L44.75 31.42L54.42 37.01V5.59Z" fill="currentColor"></path>
                        <path d="M90.73 26.79C92.09 28.13 92.77 29.85 92.77 31.94C92.77 33.45 92.39 34.81 91.64 36.03C90.89 37.25 89.76 38.22 88.26 38.93C86.76 39.65 84.94 40.01 82.77 40.01C80.88 40.01 79.07 39.73 77.34 39.16C75.6 38.59 74.18 37.81 73.08 36.8L74.81 33.82C75.72 34.67 76.87 35.36 78.28 35.87C79.69 36.39 81.18 36.64 82.77 36.64C84.73 36.64 86.26 36.22 87.35 35.4C88.44 34.57 88.99 33.43 88.99 31.97C88.99 30.51 88.45 29.38 87.37 28.58C86.29 27.78 84.66 27.37 82.47 27.37H80.36V24.73L87 16.58H74.29V13.3H91.71V15.86L84.81 24.38C87.4 24.63 89.37 25.43 90.73 26.78V26.79Z" fill="currentColor"></path>
                        <path d="M97.48 13.31H108.6C111.39 13.31 113.87 13.86 116.03 14.97C118.19 16.08 119.87 17.63 121.07 19.63C122.26 21.63 122.86 23.92 122.86 26.51C122.86 29.1 122.26 31.39 121.07 33.39C119.88 35.39 118.2 36.94 116.03 38.05C113.86 39.16 111.39 39.71 108.6 39.71H97.48V13.31ZM108.38 36.43C110.52 36.43 112.4 36.02 114.02 35.19C115.64 34.36 116.89 33.2 117.77 31.7C118.65 30.2 119.09 28.48 119.09 26.51C119.09 24.54 118.65 22.82 117.77 21.32C116.89 19.82 115.64 18.66 114.02 17.83C112.4 17 110.52 16.59 108.38 16.59H101.25V36.43H108.38Z" fill="currentColor"></path>
                        <path d="M155.18 26.36H158.8V36.65C157.49 37.73 155.97 38.56 154.24 39.14C152.5 39.72 150.7 40.01 148.81 40.01C146.14 40.01 143.74 39.43 141.61 38.26C139.47 37.09 137.79 35.48 136.58 33.43C135.36 31.38 134.75 29.07 134.75 26.51C134.75 23.95 135.36 21.63 136.58 19.57C137.8 17.51 139.48 15.9 141.63 14.74C143.78 13.58 146.2 13 148.89 13C151 13 152.92 13.35 154.64 14.04C156.36 14.73 157.83 15.74 159.03 17.08L156.69 19.42C154.58 17.38 152.03 16.37 149.03 16.37C147.02 16.37 145.22 16.8 143.62 17.67C142.02 18.54 140.77 19.74 139.87 21.29C138.96 22.84 138.51 24.58 138.51 26.51C138.51 28.44 138.96 30.15 139.87 31.7C140.77 33.25 142.03 34.46 143.62 35.34C145.22 36.22 147.01 36.66 148.99 36.66C151.35 36.66 153.41 36.09 155.17 34.96V26.36H155.18Z" fill="currentColor"></path>
                        <path d="M182.71 39.71L177.01 31.6C176.31 31.65 175.75 31.68 175.35 31.68H168.83V39.71H165.06V13.31H175.35C178.77 13.31 181.46 14.13 183.42 15.76C185.38 17.39 186.36 19.64 186.36 22.51C186.36 24.55 185.86 26.28 184.85 27.71C183.84 29.14 182.41 30.19 180.55 30.84L186.81 39.7H182.7L182.71 39.71ZM180.71 26.92C181.97 25.89 182.6 24.42 182.6 22.51C182.6 20.6 181.97 19.13 180.71 18.12C179.45 17.1 177.63 16.59 175.24 16.59H168.83V28.47H175.24C177.63 28.47 179.45 27.96 180.71 26.92Z" fill="currentColor"></path>
                        <path d="M196.25 38.25C194.11 37.08 192.43 35.47 191.22 33.4C190 31.34 189.39 29.04 189.39 26.5C189.39 23.96 190 21.66 191.22 19.6C192.44 17.54 194.12 15.92 196.25 14.75C198.39 13.58 200.79 13 203.45 13C206.11 13 208.48 13.58 210.62 14.75C212.76 15.92 214.43 17.53 215.64 19.58C216.85 21.63 217.45 23.94 217.45 26.5C217.45 29.06 216.85 31.37 215.64 33.42C214.43 35.47 212.76 37.08 210.62 38.25C208.48 39.42 206.09 40 203.45 40C200.81 40 198.38 39.42 196.25 38.25ZM208.67 35.33C210.22 34.45 211.44 33.24 212.33 31.69C213.22 30.14 213.67 28.42 213.67 26.5C213.67 24.58 213.22 22.86 212.33 21.31C211.44 19.76 210.22 18.55 208.67 17.67C207.12 16.79 205.38 16.35 203.45 16.35C201.52 16.35 199.76 16.79 198.19 17.67C196.62 18.55 195.39 19.76 194.49 21.31C193.59 22.86 193.15 24.59 193.15 26.5C193.15 28.41 193.6 30.14 194.49 31.69C195.38 33.24 196.61 34.45 198.19 35.33C199.76 36.21 201.51 36.65 203.45 36.65C205.39 36.65 207.13 36.21 208.67 35.33Z" fill="currentColor"></path>
                        <path d="M224.98 36.99C223.02 34.98 222.04 32.09 222.04 28.32V13.31H225.81V28.17C225.81 33.83 228.29 36.66 233.24 36.66C235.65 36.66 237.5 35.96 238.78 34.57C240.06 33.18 240.7 31.04 240.7 28.18V13.32H244.36V28.33C244.36 32.13 243.38 35.02 241.42 37.02C239.46 39.02 236.72 40.02 233.2 40.02C229.68 40.02 226.94 39.02 224.98 37V36.99Z" fill="currentColor"></path>
                        <path d="M269.52 15.76C271.48 17.39 272.46 19.64 272.46 22.51C272.46 25.38 271.48 27.63 269.52 29.26C267.56 30.89 264.87 31.71 261.45 31.71H254.93V39.71H251.16V13.31H261.45C264.87 13.31 267.56 14.13 269.52 15.76ZM266.8 26.9C268.06 25.88 268.69 24.42 268.69 22.51C268.69 20.6 268.06 19.13 266.8 18.12C265.54 17.1 263.72 16.59 261.33 16.59H254.92V28.43H261.33C263.72 28.43 265.54 27.92 266.8 26.9Z" fill="currentColor"></path>
                    </g>
                    <defs>
                        <clipPath id="clip0_29_1308">
                            <rect width="272.46" height="52.72" fill="white"></rect>
                        </clipPath>
                    </defs>
                </svg>
            </a>
            <ul class="footer__requisites">
                <li><b>Реквизиты компании:</b></li>
                <li><b>ИНН:</b> 480206344141</li>
                <li><b>ОГРНИП:</b> 321482700013249</li>
            </ul>
        </div>
        <nav class="footer__nav">
            <ul class="footer__nav-menu">
                <li><a class="footer__nav-link js-scrollto" href="/index.php/#solutions">Готовые решения</a></li>
                <li><a class="footer__nav-link js-scrollto" href="/index.php/#individual">Индивидуальное внедрение</a></li>
                <li><a class="footer__nav-link js-scrollto" href="/index.php/#cases">Кейсы</a></li>
                <li><a class="footer__nav-link js-scrollto" href="/index.php/#about">О компании</a></li>
            </ul>
            <ul class="footer__nav-social">
                <li>
                    <a class="footer__nav-link" href="https://wa.me/79675553444?text=" target="_blank">
                        <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.5013 2.91675C25.5557 2.91675 32.0847 9.44571 32.0847 17.5001C32.0847 25.5545 25.5557 32.0834 17.5013 32.0834C14.9241 32.0879 12.3922 31.4058 10.1659 30.1074L2.92382 32.0834L4.89549 24.8384C3.59606 22.6114 2.91347 20.0784 2.91799 17.5001C2.91799 9.44571 9.44695 2.91675 17.5013 2.91675ZM12.5313 10.6459L12.2397 10.6576C12.0511 10.6706 11.8668 10.7201 11.6972 10.8034C11.539 10.8931 11.3946 11.0051 11.2684 11.1359C11.0934 11.3007 10.9942 11.4436 10.8878 11.5822C10.3484 12.2835 10.058 13.1445 10.0624 14.0292C10.0653 14.7438 10.2519 15.4395 10.5436 16.0899C11.1401 17.4053 12.1215 18.798 13.4165 20.0886C13.7286 20.3992 14.0349 20.7113 14.3644 21.0015C15.9736 22.4182 17.8911 23.4398 19.9644 23.9853L20.7928 24.1122C21.0626 24.1267 21.3324 24.1063 21.6036 24.0932C22.0283 24.0708 22.4429 23.9558 22.8184 23.7563C23.0092 23.6577 23.1956 23.5506 23.3769 23.4355C23.3769 23.4355 23.4387 23.3937 23.5592 23.3042C23.7561 23.1584 23.8772 23.0549 24.0405 22.8842C24.163 22.7579 24.2651 22.6111 24.3467 22.4438C24.4605 22.2061 24.5742 21.7526 24.6209 21.3749C24.6559 21.0861 24.6457 20.9286 24.6413 20.8309C24.6355 20.6749 24.5057 20.513 24.3642 20.4445L23.5155 20.0638C23.5155 20.0638 22.2467 19.5111 21.4709 19.1582C21.3897 19.1229 21.3027 19.1026 21.2142 19.0984C21.1145 19.088 21.0136 19.0991 20.9185 19.1311C20.8234 19.163 20.7362 19.215 20.663 19.2836C20.6557 19.2807 20.558 19.3638 19.5036 20.6413C19.4431 20.7227 19.3597 20.7841 19.2642 20.8179C19.1686 20.8516 19.0651 20.8562 18.9669 20.8309C18.8719 20.8056 18.7788 20.7734 18.6884 20.7347C18.5076 20.6588 18.4449 20.6297 18.3209 20.5772C17.4836 20.2124 16.7086 19.7189 16.024 19.1145C15.8403 18.954 15.6697 18.779 15.4947 18.6099C14.921 18.0604 14.421 17.4388 14.0072 16.7607L13.9211 16.6222C13.8602 16.5285 13.8103 16.4282 13.7724 16.3232C13.7169 16.1088 13.8613 15.9367 13.8613 15.9367C13.8613 15.9367 14.2157 15.5488 14.3805 15.3388C14.5409 15.1347 14.6765 14.9363 14.764 14.7949C14.9361 14.5178 14.9901 14.2334 14.8997 14.0132C14.4913 13.0157 14.0694 12.0236 13.6338 11.0367C13.5478 10.8413 13.2926 10.7013 13.0607 10.6736C12.9819 10.6639 12.9032 10.6561 12.8244 10.6503C12.6286 10.6391 12.4323 10.641 12.2367 10.6561L12.5313 10.6459Z" fill="currentColor" />
                        </svg>
                    </a>
                </li>
                <li>
                    <a class="footer__nav-link" href="https://t.me/+79675553444" target="_blank">
                        <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.9536 28.8605V28.8576L26.9798 28.7949L31.3534 6.74489V6.67489C31.3534 6.1251 31.1492 5.64531 30.7088 5.35802C30.3223 5.10572 29.8775 5.08822 29.5654 5.11156C29.275 5.13806 28.9884 5.19675 28.7109 5.28656C28.5924 5.32434 28.4756 5.36716 28.3609 5.41489L28.3419 5.42218L3.95856 14.9874L3.95127 14.9903C3.87656 15.0141 3.8039 15.0438 3.73398 15.0793C3.56066 15.1574 3.3943 15.2501 3.23668 15.3563C2.92314 15.5722 2.32668 16.0797 2.42731 16.8847C2.51043 17.5526 2.96981 17.9755 3.28043 18.1957C3.46337 18.3249 3.66057 18.4325 3.86814 18.5166L3.91481 18.537L3.92939 18.5413L3.9396 18.5457L8.20668 19.9822C8.19113 20.2505 8.21884 20.5203 8.28981 20.7916L10.4263 28.8984C10.543 29.3403 10.7952 29.7346 11.1474 30.0258C11.4997 30.317 11.9343 30.4906 12.3903 30.5221C12.8463 30.5536 13.3007 30.4416 13.6897 30.2016C14.0787 29.9617 14.3827 29.6059 14.5592 29.1843L17.8959 25.6172L23.6256 30.0097L23.7073 30.0447C24.2279 30.2722 24.7136 30.3436 25.1584 30.2838C25.6031 30.2226 25.9561 30.0359 26.2215 29.8245C26.5285 29.5757 26.7732 29.2585 26.9361 28.8984L26.9477 28.8736L26.9521 28.8649L26.9536 28.8605ZM10.4044 20.2345C10.3807 20.1445 10.3864 20.0494 10.4204 19.9628C10.4545 19.8763 10.5153 19.8029 10.594 19.7532L25.0621 10.5657C25.0621 10.5657 25.9138 10.048 25.8831 10.5657C25.8831 10.5657 26.0348 10.6561 25.5784 11.0805C25.1467 11.4845 15.2665 21.0234 14.2661 21.9888C14.2118 22.0439 14.173 22.1123 14.1538 22.1872L12.5409 28.3413L10.4044 20.2345Z" fill="currentColor" />
                        </svg>
                    </a>
                </li>
            </ul>
            <span class="footer__nav-divider"></span>
            <table class="footer__nav-contacts">
                <tr>
                    <td><a class="footer__nav-link" href="tel:+79675553444">+7 (967) 555-34-44</a></td>
                    <th>Номер</th>
                </tr>
                <tr>
                    <td><a class="footer__nav-link" href="mailto:info@3d-group.space">info@3d-group.space</a></td>
                    <th>Почта</th>
                </tr>
                <tr>
                    <td>г. Самара, Московское шоссе, 55</td>
                    <th>Адрес офиса</th>
                </tr>
            </table>
        </nav>
        <hr class="footer__line">
        <div class="footer__copyright">
            <p class="footer__copyright-text">© 3D GROUP | <?= date('Y') ?></p>
            <ul class="footer__copyright-list">
                <li><a href="/policy/">Пользовательское соглашение</a></li>
                <li><a href="/offer/">Договор оферты</a></li>
            </ul>
        </div>
    </div>
</footer>

<!-- Подключение скриптов -->
<script defer src="<?= SITE_TEMPLATE_PATH ?>/js/plugins.js"></script>
<script defer src="<?= SITE_TEMPLATE_PATH ?>/js/scripts.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Элементы DOM для popup
        const thankYouPopup = document.getElementById('thankYouPopup');
        const popupOverlay = document.getElementById('popupOverlay');
        const closePopupBtn = document.getElementById('closePopupBtn');
        const thankYouDecorBottom = document.getElementById('thankYouDecorBottom');

        // Функция для показа popup
        function showPopup() {
            if (thankYouPopup && popupOverlay && thankYouDecorBottom && closePopupBtn) {
                thankYouPopup.style.display = 'flex';
                popupOverlay.style.display = 'block';
                thankYouDecorBottom.style.display = 'block';
                closePopupBtn.style.display = 'flex'; // Показываем кнопку
                setTimeout(() => {
                    thankYouPopup.classList.add('show');
                }, 10);

                // Блокируем прокрутку body
                document.body.style.overflow = 'hidden';
            }
        }

        // Функция для скрытия popup
        function hidePopup() {
            if (thankYouPopup && popupOverlay && thankYouDecorBottom && closePopupBtn) {
                thankYouPopup.classList.remove('show');
                setTimeout(() => {
                    thankYouPopup.style.display = 'none';
                    popupOverlay.style.display = 'none';
                    thankYouDecorBottom.style.display = 'none';
                    closePopupBtn.style.display = 'none'; // Скрываем кнопку
                    // Восстанавливаем прокрутку body
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // Изначально скрываем кнопку
        if (closePopupBtn) {
            closePopupBtn.style.display = 'none';
        }

        // Обработчики событий
        if (closePopupBtn) {
            closePopupBtn.addEventListener('click', hidePopup);
        }

        if (popupOverlay) {
            popupOverlay.addEventListener('click', hidePopup);
        }

        // Закрытие по клавише Esc
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hidePopup();
            }
        });

        // Проверяем якорь в URL для автоматического показа
        if (window.location.hash.includes('#form-success')) {
            showPopup();
            // Убираем якорь из URL
            history.replaceState(null, null, window.location.pathname);
        }

        // Глобальная функция для показа popup (можно вызывать из других скриптов)
        window.showThankYouPopup = showPopup;
        window.hideThankYouPopup = hidePopup;
    });
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        for (var j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');

    ym(100193584, 'init', {
        webvisor: true,
        clickmap: true,
        accurateTrackBounce: true,
        trackLinks: true
    });
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/100193584" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->

</body>

</html>