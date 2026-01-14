<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<!-- Intro :: Start-->
<section class="intro">
    <div class="container-fluid">
        <div class="intro__grid">
            <div class="intro__heading">
                <h1 class="intro__title">
                    <span class="intro__title-slide"><?= htmlspecialchars($arResult['TITLE_WORD']) ?></span>
                </h1>
                <h2 class="intro__subtitle">
                    <span class="intro__subtitle-slide"><?= htmlspecialchars($arResult['SUBTITLE']) ?></span>
                </h2>
            </div>

            <div class="intro__card">
                <mark class="intro__card-mark"><?= htmlspecialchars($arResult['CARD_MARK']) ?></mark>
                <?php if ($arResult['CARD_IMAGE']): ?>
                    <picture class="intro__card-image">
                        <img src="<?= htmlspecialchars($arResult['CARD_IMAGE']) ?>" alt="<?= htmlspecialchars($arResult['SUBTITLE']) ?>">
                    </picture>
                <?php endif; ?>
                <?php if (!empty($arResult['TAGS'])): ?>
                    <ul class="intro__card-tags">
                        <?php foreach ($arResult['TAGS'] as $tag): ?>
                            <li><?= htmlspecialchars($tag) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="intro__action">
                <a class="ui-btn ui-btn--gradient" href="<?= htmlspecialchars($arResult['BUTTON_LINK']) ?>" data-fancybox=""><?= htmlspecialchars($arResult['BUTTON_TEXT']) ?></a>
            </div>

            <table class="intro__table">
                <tr>
                    <th>Колличество<br> рабочих мест</th>
                    <td>
                        <mark><?= htmlspecialchars($arResult['WORKPLACES']) ?></mark>
                    </td>
                </tr>
                <tr>
                    <th>Реализация<br> проекта</th>
                    <td>
                        <mark><?= htmlspecialchars($arResult['PROJECT_DAYS']) ?> <sub>дней</sub></mark>
                    </td>
                </tr>
                <tr>
                    <th>Лицензия</th>
                    <td><?= htmlspecialchars_decode($arResult['LICENSE']) ?></td>
                </tr>
            </table>
        </div>
    </div>
</section>
<!-- Intro :: End-->