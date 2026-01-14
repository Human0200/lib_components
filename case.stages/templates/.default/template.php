<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<!-- Stages :: Start-->
<section class="stages">
    <div class="container-fluid">
        <h2 class="stages__title"><?=htmlspecialchars($arResult['TITLE'])?></h2>
        
        <?php if(!empty($arResult['STAGES'])): ?>
        <ul class="stages__list">
            <?php foreach($arResult['STAGES'] as $stage): ?>
            <li>
                <div class="stages__card">
                    <div class="stages__card-head">
                        <span class="stages__card-number"><?=$stage['NUMBER']?></span>
                        <h3 class="stages__card-title"><?=nl2br(htmlspecialchars($stage['TITLE']))?></h3>
                    </div>
                    <div class="stages__card-body">
                        <?php if($stage['TEXT']): ?>
                        <div class="stages__card-desc js-scrollbar">
                           <?=htmlspecialchars_decode($stage['TEXT'])?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($stage['IMAGE']): ?>
                        <picture class="stages__card-image">
                            <img src="<?=htmlspecialchars($stage['IMAGE'])?>" alt="<?=htmlspecialchars($stage['TITLE'])?>">
                        </picture>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</section>
<!-- Stages :: End-->