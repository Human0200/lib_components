<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<section class="topbar">
    <div class="container-fluid">
        <picture class="topbar__image">
            <img alt="" src="<?=htmlspecialchars($arResult['IMAGE'])?>">
        </picture>
        <div class="topbar__grid">
            <p class="topbar__tagline">
                <span><?=htmlspecialchars($arResult['TAGLINE'])?></span>
            </p>
            <h1 class="topbar__title">
                <span><?=htmlspecialchars($arResult['TITLE'])?></span>
                <small><?=htmlspecialchars($arResult['SUBTITLE'])?></small>
            </h1>

        </div>
        
        <?php if (!empty($arResult['CARDS'])): ?>
        <ul class="topbar__cards">
            <?php foreach($arResult['CARDS'] as $index => $card): ?>
            <li>
                <div class="topbar__card">
                    <span class="topbar__card-text"><?=htmlspecialchars($card['TEXT'])?></span>
                    <span class="topbar__card-title">
                        <?php if ($index === 0): ?>
                            <?=htmlspecialchars($card['NUMBER'])?> <small><?=htmlspecialchars($card['LABEL'])?></small>
                        <?php elseif ($index === 1): ?>
                            <small><?=htmlspecialchars($card['PREFIX'])?></small> <?=htmlspecialchars($card['NUMBER'])?> <small><?=htmlspecialchars($card['LABEL'])?></small>
                        <?php else: ?>
                            <sub><?=htmlspecialchars($card['PREFIX'])?></sub> <?=htmlspecialchars($card['NUMBER'])?> <small><?=htmlspecialchars($card['LABEL'])?></small>
                        <?php endif; ?>
                    </span>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        
        <?php if (!empty($arResult['BUTTONS'])): ?>
        <div class="topbar__action">
            <?php foreach($arResult['BUTTONS'] as $button): ?>
                <a class="<?=htmlspecialchars($button['CLASS'])?>" 
                   <?php if ($button['CURSOR']): ?>data-cursor="<?=htmlspecialchars($button['CURSOR'])?>"<?php endif; ?>
                   href="<?=htmlspecialchars($button['LINK'])?>" data-fancybox="">
                    <?=htmlspecialchars($button['TEXT'])?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>