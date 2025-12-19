<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>

<section class="features">
    <div class="container-fluid">
        <span class="features__cube features__cube--01"></span>
        <span class="features__cube features__cube--02"></span>
        <h2 class="features__title"><?= $arParams['TITLE'] ?></h2>
        <p class="features__tagline">
            <span class="features__tagline-row"><?= $arParams['TAGLINE_ROW1'] ?></span>
            <span class="features__tagline-row"><?= $arParams['TAGLINE_ROW2'] ?></span>
        </p>
        <ul class="features__list">
            <?php foreach ($arParams['FEATURES_ARRAY'] as $feature): ?>
                <li><?= $feature ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>