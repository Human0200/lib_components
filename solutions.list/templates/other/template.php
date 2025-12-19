<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<section class="other">
    <div class="container-fluid">
        <h2 class="other__title"><?=$arResult['TITLE']?></h2>
        <div class="other__swiper">
            <div class="swiper js-swiper-other">
                <div class="swiper-wrapper">
                    <?php foreach($arResult['ITEMS'] as $item): ?>
                    <div class="swiper-slide">
                        <a class="other__card" <?=!empty($item['DETAIL_PAGE_URL']) ? 'href="'.$item['DETAIL_PAGE_URL'].'"' : ''?>>
                            <picture class="other__card-image">
                                <img src="<?=htmlspecialchars($item['IMAGE'])?>" alt="">
                            </picture>
                            <h3 class="other__card-title"><?=htmlspecialchars($item['NAME'])?></h3>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>