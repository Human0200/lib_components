<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<section class="results">
    <div class="container-fluid">
        <h2 class="results__title"><?=htmlspecialchars($arResult['TITLE'])?></h2>
        <mark class="results__mark"><?=htmlspecialchars($arResult['MARK'])?></mark>
        
        <?php if (!empty($arResult['CARDS'])): ?>
        <ul class="results__list">
            <?php foreach ($arResult['CARDS'] as $card): ?>
            <li>
                <div class="results__card">
                    <?php if (!empty($card['IMAGE'])): ?>
                    <picture class="results__card-image">
                        <img src="<?=htmlspecialchars($card['IMAGE'])?>" alt="">
                    </picture>
                    <?php endif; ?>
                    
                    <?php if (!empty($card['TITLE'])): ?>
                    <h3 class="results__card-title"><?=htmlspecialchars($card['TITLE'])?></h3>
                    <?php endif; ?>
                    
                    <?php if (!empty($card['ITEMS'])): ?>
                    <ul class="results__card-list">
                        <?php foreach ($card['ITEMS'] as $item): ?>
                        <li>
                            <span class="results__card-pct"><?php if (!empty($item['NUMBER'])): ?><span><?=htmlspecialchars($item['NUMBER'])?></span><?php endif; ?><?php if (!empty($item['PCT'])): ?><?=htmlspecialchars($item['PCT'])?><?php endif; ?> <?php if (!empty($item['TEXT'])): ?><small><?=nl2br(htmlspecialchars($item['TEXT']))?></small><?php endif; ?></span>
                            <span class="results__card-line"></span>
                            <?php if (!empty($item['CAPTION'])): ?>
                            <span class="results__card-caption"><?=nl2br(htmlspecialchars($item['CAPTION']))?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</section>