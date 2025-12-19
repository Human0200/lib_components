<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<!-- License :: Start-->
<section class="license">
    <div class="container-fluid">
        <mark class="license__mark">
            <?= htmlspecialchars($arResult['MARK_TEXT']) ?>
            <mark><?= htmlspecialchars($arResult['MARK_HIGHLIGHT']) ?></mark>
        </mark>

        <div class="license__group">
            <div class="license__group-head">
                <div class="row">
                    <div class="col-xl">
                        <h2 class="license__title"><?= htmlspecialchars($arResult['TITLE']) ?></h2>
                    </div>
                    <div class="col-xl-auto">
                        <div class="license__caption">
                            <p><?= htmlspecialchars($arResult['CAPTION']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="license__group-body">
                <div class="license__tabs" data-tabs>
                    <div class="license__tabs-control">
                        <button class="license__tabs-btn is-active" data-tabs-btn="cloud">Облачная версия</button>
                        <button class="license__tabs-btn" data-tabs-btn="boxed">Коробочная версия</button>
                    </div>

                    <div class="license__tabs-wrapper">
                        <!-- Облачная версия -->
                        <div class="license__tabs-content is-active" data-tabs-content="cloud">
                            <button class="license__toggle license__toggle--right is-active" data-tabs-btn="boxed">
                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>Коробочная версия<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </button>

                            <?php if (!empty($arResult['CLOUD_LICENSES'])): ?>
                                <div class="license__swiper">
                                    <div class="swiper js-swiper-license">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($arResult['CLOUD_LICENSES'] as $index => $license): ?>
                                                <?php
                                                // Последний элемент - статичная карточка с выбором
                                                $isLast = ($index === count($arResult['CLOUD_LICENSES']) - 1);
                                                $isPopular = ($license['TYPE'] == 'popular');
                                                ?>
                                                <div class="swiper-slide">
                                                    <div class="license__card license__card--cloud<?= ($isLast ? ' license__card--static' : '') ?>" <?= ($isLast ? ' data-card' : '') ?>>
                                                        <div class="license__card-flip">
                                                            <!-- Лицевая сторона -->
                                                            <div class="license__card-front">
                                                                <div class="license__card-head">
                                                                    <h3 class="license__card-title"><?= htmlspecialchars($license['NAME']) ?></h3>
                                                                    <?php if ($license['DESCRIPTION']): ?>
                                                                        <p class="license__card-text"><?= htmlspecialchars($license['DESCRIPTION']) ?></p>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="license__card-body" <?= (!$isLast ? ' data-ruler' : '') ?>>
                                                                    <?php if ($isLast): ?>
                                                                        <!-- Статичная карточка с выбором -->
                                                                        <div class="license__card-employees">
                                                                            <div class="license__card-employees-select" data-card-select>
                                                                                <button class="license__card-employees-toggle" data-card-toggle>
                                                                                    <span class="license__card-employees-number js-price-number"><?= htmlspecialchars($license['EMPLOYEES']) ?></span>
                                                                                    <span class="license__card-employees-text">сотрудников</span>
                                                                                </button>
                                                                                <div class="license__card-employees-dropdown">
                                                                                    <ul class="license__card-employees-menu" data-card-menu>
                                                                                        <?php if (!empty($arResult['ENTERPRISE_OPTIONS'])): ?>
                                                                                            <?php foreach ($arResult['ENTERPRISE_OPTIONS'] as $option): ?>
                                                                                                <li data-price-month="<?= htmlspecialchars($option['PRICE_MONTH']) ?>"
                                                                                                    data-price-number="<?= htmlspecialchars($option['EMPLOYEES']) ?>"
                                                                                                    data-price-year="<?= htmlspecialchars($option['PRICE_YEAR']) ?>">
                                                                                                    <b><?= htmlspecialchars($option['EMPLOYEES']) ?></b> сотрудников
                                                                                                </li>
                                                                                            <?php endforeach; ?>
                                                                                        <?php else: ?>
                                                                                            <li data-price-month="33 990 ₽/мес." data-price-number="250" data-price-year="326 280 ₽/год">
                                                                                                <b>250</b> сотрудников
                                                                                            </li>
                                                                                        <?php endif; ?>
                                                                                    </ul>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <!-- Обычная карточка -->
                                                                        <p class="license__card-employees">
                                                                            <span class="license__card-employees-number"><?= htmlspecialchars($license['EMPLOYEES']) ?></span>
                                                                            <span class="license__card-employees-text">сотрудников</span>
                                                                            <span class="license__card-employees-mark">
                                                                                <?php if ($isPopular): ?>
                                                                                    <svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3505 16.5C13.3505 13.475 11.1772 13.9535 9.06113 9.625C6.56006 11.1154 4.96225 13.6764 4.77177 16.5C4.9095 18.6305 5.94166 20.6157 7.63134 22H6.6162C3.00105 20.7259 0.566833 17.4518 0.482422 13.75C0.482422 7.5075 6.90215 1.66788 10.4909 0C9.80462 5.76262 17.6398 7.26962 17.6398 14.7812C17.6398 20.1121 11.5061 22 11.5061 22H10.4909C12.2875 20.6989 13.346 18.6629 13.3505 16.5Z" fill="#FB6F3D" />
                                                                                    </svg>Популярный
                                                                                <?php endif; ?>
                                                                            </span>
                                                                        </p>
                                                                    <?php endif; ?>

                                                                    <hr class="license__card-line">

                                                                    <p class="license__card-price">
                                                                        <?php if ($isLast): ?>
                                                                            <sup class="js-price-month"><?= number_format($license['PRICE_MONTH'], 0, '', ' ') ?> ₽/мес.</sup>
                                                                            <span>
                                                                                <span class="js-price-year"><?= number_format($license['PRICE_YEAR_DISCOUNTED'], 0, '', ' ') ?> ₽/год</span>
                                                                                <?php if ($license['DISCOUNT'] > 0): ?>
                                                                                    <mark>-<?= $license['DISCOUNT'] ?>%</mark>
                                                                                <?php endif; ?>
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <sup><?= number_format($license['PRICE_MONTH'], 0, '', ' ') ?> ₽/мес.</sup>
                                                                            <span>
                                                                                <?= number_format($license['PRICE_YEAR_DISCOUNTED'], 0, '', ' ') ?> ₽/год
                                                                                <?php if ($license['DISCOUNT'] > 0): ?>
                                                                                    <mark>-<?= $license['DISCOUNT'] ?>%</mark>
                                                                                <?php endif; ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                        <sub>за всех пользователей</sub>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <!-- Обратная сторона -->
                                                            <div class="license__card-back">
                                                                <div class="license__card-head">
                                                                    <h3 class="license__card-title"><?= htmlspecialchars($license['NAME']) ?></h3>
                                                                    <?php if ($license['DESCRIPTION']): ?>
                                                                        <p class="license__card-text"><?= htmlspecialchars($license['DESCRIPTION']) ?></p>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="license__card-body" <?= (!$isLast ? ' data-ruler' : '') ?>>
                                                                    <ul class="license__card-list">
                                                                        <?php if (!empty($license['FEATURES'])): ?>
                                                                            <?php foreach ($license['FEATURES'] as $feature): ?>
                                                                                <li><?= htmlspecialchars($feature) ?></li>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>

                                                                        <?php if (!empty($license['FEATURES_DISABLED'])): ?>
                                                                            <?php foreach ($license['FEATURES_DISABLED'] as $feature): ?>
                                                                                <li class="is-disabled"><?= htmlspecialchars($feature) ?></li>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Коробочная версия -->
                        <div class="license__tabs-content" data-tabs-content="boxed">
                            <button class="license__toggle license__toggle--left" data-tabs-btn="cloud">
                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>облачная версия<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </button>

                            <?php if (!empty($arResult['BOXED_LICENSES'])): ?>
                                <div class="license__swiper">
                                    <div class="swiper js-swiper-license">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($arResult['BOXED_LICENSES'] as $license): ?>
                                                <div class="swiper-slide">
                                                    <div class="license__card license__card--boxed" data-ruler>
                                                        <div class="license__card-flip">
                                                            <!-- Лицевая сторона -->
                                                            <div class="license__card-front">
                                                                <div class="license__card-head">
                                                                    <h3 class="license__card-title"><?= nl2br(htmlspecialchars($license['NAME'])) ?></h3>
                                                                </div>
                                                                <div class="license__card-body">
                                                                    <p class="license__card-employees">
                                                                        <span class="license__card-employees-number"><?= htmlspecialchars($license['EMPLOYEES']) ?></span>
                                                                        <span class="license__card-employees-text">сотрудников</span>
                                                                        <span class="license__card-employees-mark"></span>
                                                                    </p>
                                                                    <hr class="license__card-line">
                                                                    <p class="license__card-yearly">
                                                                        <span><?= number_format($license['PRICE_YEAR'], 0, '', ' ') ?> ₽</span>
                                                                        <sub>Лицензия 12 месяцев</sub>
                                                                    </p>
                                                                    <?php if ($license['DISCOUNT'] > 0): ?>
                                                                        <p class="license__card-renewal">
                                                                            <span>

                                                                                <?

                                                                                if (number_format($license['PRICE_YEAR_DISCOUNTED'], 0, '', ' ') != number_format($license['PRICE_YEAR'], 0, '', ' ')) {
                                                                                    number_format($license['PRICE_YEAR_DISCOUNTED'], 0, '', ' ');
                                                                                } else {
                                                                                    echo number_format($license['PRICE_MONTH'], 0, '', ' ');
                                                                                }
                                                                                ?> ₽
                                                                                <mark>-<?= $license['DISCOUNT'] ?>%</mark>
                                                                            </span>
                                                                            <sub>Продление лицензии</sub>
                                                                        </p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <!-- Обратная сторона -->
                                                            <div class="license__card-back">
                                                                <div class="license__card-head">
                                                                    <h3 class="license__card-title"><?= nl2br(htmlspecialchars($license['NAME'])) ?></h3>
                                                                </div>
                                                                <div class="license__card-body">
                                                                    <ul class="license__card-list">
                                                                        <?php if (!empty($license['FEATURES'])): ?>
                                                                            <?php foreach ($license['FEATURES'] as $feature): ?>
                                                                                <li><?= htmlspecialchars($feature) ?></li>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>

                                                                        <?php if (!empty($license['FEATURES_DISABLED'])): ?>
                                                                            <?php foreach ($license['FEATURES_DISABLED'] as $feature): ?>
                                                                                <li class="is-disabled"><?= htmlspecialchars($feature) ?></li>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="license__group-foot">
                <div class="license__consult">
                    <p class="license__consult-text"><?=nl2br($arResult['MORE_CAPTION'])?></p>
                    <div class="license__consult-action">
                        <a class="ui-btn ui-btn--gradient" href="#modal-feedback" data-fancybox="">Получить бесплатную консультацию</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- License :: End-->

<!-- Subsc :: Start-->
<section class="subsc">
    <div class="container-fluid">
        <div class="subsc__group">
            <div class="subsc__group-head">
                <h2 class="subsc__title">+ подписка на маркетплейс</h2>
                <div class="subsc__desc">
                    <p>Расширяйте возможности вашего Битрикс24! Интеграции с соцсетями и мессенджерами (VK, Telegram и другие).</p>
                    <p>За фиксированную цену используйте любое количество приложений и бизнес-сценариев из более чем 4 200 доступных в каталоге Битрикс24 Маркетплейс.</p>
                    <p>Устанавливайте столько приложений, сколько вам нужно.</p>
                </div>
            </div>

            <div class="subsc__group-body">
                <div class="subsc__tabs" data-tabs>
                    <div class="subsc__tabs-control">
                        <button class="subsc__tabs-btn is-active" data-tabs-btn="cloud">Облачная версия</button>
                        <button class="subsc__tabs-btn" data-tabs-btn="boxed">Коробочная версия</button>
                    </div>

                    <div class="subsc__tabs-wrapper">
                        <!-- Облачные подписки -->
                        <div class="subsc__tabs-content is-active" data-tabs-content="cloud">
                            <button class="subsc__toggle subsc__toggle--right is-active" data-tabs-btn="boxed">
                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>Коробочная версия<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </button>

                            <?php
                            // Фильтруем подписки для облачной версии
                            $cloudSubscriptions = array_filter($arResult['SUBSCRIPTIONS'], function ($s) {
                                return $s['TYPE'] == 'cloud';
                            });

                            // // ОТЛАДКА
                            // echo '<pre>';
                            // print_r($arResult['SUBSCRIPTION_CLOUD_OPTIONS']);
                            // echo '</pre>';
                            ?>

                            <?php if (!empty($cloudSubscriptions)): ?>
                                <div class="subsc__swiper">
                                    <div class="swiper js-swiper-subsc">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($cloudSubscriptions as $subscription): ?>
                                                <div class="swiper-slide">
                                                    <div class="subsc__card subsc__card--cloud">
                                                        <div class="subsc__card-head">
                                                            <?php if ($subscription['DISCOUNT'] > 0): ?>
                                                                <span class="subsc__card-badge">-<?= $subscription['DISCOUNT'] ?>%</span>
                                                            <?php endif; ?>
                                                            <p class="subsc__card-text">Для тарифа</p>
                                                            <h3 class="subsc__card-title"><?= nl2br(htmlspecialchars($subscription['NAME'])) ?></h3>
                                                            <?php if ($subscription['TYPE'] == 'popular'): ?>
                                                                <span class="subsc__card-mark">
                                                                    <svg width="18" height="22" viewBox="0 0 18 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3505 16.5C13.3505 13.475 11.1772 13.9535 9.06113 9.625C6.56006 11.1154 4.96225 13.6764 4.77177 16.5C4.9095 18.6305 5.94166 20.6157 7.63134 22H6.6162C3.00105 20.7259 0.566833 17.4518 0.482422 13.75C0.482422 7.5075 6.90215 1.66788 10.4909 0C9.80462 5.76262 17.6398 7.26962 17.6398 14.7812C17.6398 20.1121 11.5061 22 11.5061 22H10.4909C12.2875 20.6989 13.346 18.6629 13.3505 16.5Z" fill="#FB6F3D" />
                                                                    </svg>Популярный
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="subsc__card-body">
                                                            <p class="subsc__card-employees">
                                                                <span class="subsc__card-employees-number"><?= htmlspecialchars($subscription['EMPLOYEES']) ?></span>
                                                                <span class="subsc__card-employees-text">сотрудников</span>
                                                            </p>
                                                            <hr class="subsc__card-line">
                                                            <p class="subsc__card-price">
                                                                <sup><?= number_format($subscription['PRICE_MONTH'], 0, '', ' ') ?> ₽/мес.</sup>
                                                                <span><?= number_format($subscription['PRICE_YEAR_DISCOUNTED'], 0, '', ' ') ?> ₽/год</span>
                                                                <sub>Цена с НДС</sub>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <!-- Дополнительная статическая карточка с параметрами -->
                                            <?php if (!empty($arResult['SUBSCRIPTION_CLOUD_OPTIONS'])): ?>
                                                <?php
                                                $firstOption = $arResult['SUBSCRIPTION_CLOUD_OPTIONS'][0];
                                                $priceMonthNum = intval(str_replace([' ', '₽/мес.'], '', $firstOption['PRICE_MONTH']));
                                                $priceYearNum = intval(str_replace([' ', '₽/год'], '', $firstOption['PRICE_YEAR']));

                                                ?>
                                                <div class="swiper-slide">
                                                    <div class="subsc__card subsc__card--cloud subsc__card--static" data-card>
                                                        <div class="subsc__card-head">
                                                            <span class="subsc__card-badge">-50%</span>
                                                            <p class="subsc__card-text">Для тарифа</p>
                                                            <h3 class="subsc__card-title">Энтерпрайз</h3>
                                                        </div>
                                                        <div class="subsc__card-body">
                                                            <div class="subsc__card-employees">
                                                                <div class="subsc__card-employees-select" data-card-select>
                                                                    <button class="subsc__card-employees-toggle" data-card-toggle>
                                                                        <span class="subsc__card-employees-number js-price-number"><?= htmlspecialchars($firstOption['EMPLOYEES']) ?></span>
                                                                        <span class="subsc__card-employees-text">сотрудников</span>
                                                                    </button>
                                                                    <div class="subsc__card-employees-dropdown">
                                                                        <ul class="subsc__card-employees-menu" data-card-menu>
                                                                            <?php foreach ($arResult['SUBSCRIPTION_CLOUD_OPTIONS'] as $option): ?>
                                                                                <li data-price-month="<?= htmlspecialchars($option['PRICE_MONTH']) ?>"
                                                                                    data-price-number="<?= htmlspecialchars($option['EMPLOYEES']) ?>"
                                                                                    data-price-year="<?= htmlspecialchars($option['PRICE_YEAR']) ?>">
                                                                                    <b><?= htmlspecialchars($option['EMPLOYEES']) ?></b> сотрудников
                                                                                </li>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="subsc__card-line">
                                                            <p class="subsc__card-price">
                                                                <sup class="js-price-month"><?= number_format($priceMonthNum, 0, '', ' ') ?> ₽/мес.</sup>
                                                                <span class="js-price-year"><?= number_format($priceYearNum, 0, '', ' ') ?> ₽/год</span>
                                                                <sub>Цена с НДС</sub>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Коробочные подписки -->
                        <div class="subsc__tabs-content" data-tabs-content="boxed">
                            <button class="license__toggle license__toggle--left" data-tabs-btn="cloud">
                                <svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>облачная версия<svg width="43" height="43" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.5 32.25L32.25 21.5L21.5 10.75" stroke="currentColor" stroke-width="2" />
                                    <path d="M10.75 32.25L21.5 21.5L10.75 10.75" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </button>

                            <?php
                            // Фильтруем подписки для коробочной версии
                            $boxedSubscriptions = array_filter($arResult['SUBSCRIPTIONS'], function ($s) {
                                return $s['TYPE'] == 'boxed';
                            });
                            ?>

                            <?php if (!empty($boxedSubscriptions)): ?>
                                <div class="subsc__swiper">
                                    <div class="swiper js-swiper-subsc">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($boxedSubscriptions as $subscription): ?>
                                                <div class="swiper-slide">
                                                    <div class="subsc__card subsc__card--boxed<?= ($subscription['TYPE'] == 'static' ? ' subsc__card--static' : '') ?>">
                                                        <div class="subsc__card-head">
                                                            <p class="subsc__card-text">Для тарифа</p>
                                                            <h3 class="subsc__card-title"><?= nl2br(htmlspecialchars($subscription['NAME'])) ?></h3>
                                                        </div>
                                                        <div class="subsc__card-body">
                                                            <p class="subsc__card-employees">
                                                                <span class="subsc__card-employees-number"><?= htmlspecialchars($subscription['EMPLOYEES']) ?></span>
                                                                <span class="subsc__card-employees-text">сотрудников</span>
                                                            </p>
                                                            <hr class="subsc__card-line">
                                                            <p class="subsc__card-price">
                                                                <small>Подписка 12 месяцев</small>
                                                                <span>
                                                                    <?= number_format($subscription['PRICE_YEAR_DISCOUNTED'], 0, '', ' ') ?> ₽
                                                                    <?php if ($subscription['DISCOUNT'] > 0): ?>
                                                                        <mark>-<?= $subscription['DISCOUNT'] ?>%</mark>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <sub>Цена с НДС</sub>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="subsc__group-foot">
                <p class="subsc__tagline">
                    <span class="subsc__tagline-row"><?= htmlspecialchars($arResult['FOOTER_TEXT']) ?></span>
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Subsc :: End-->