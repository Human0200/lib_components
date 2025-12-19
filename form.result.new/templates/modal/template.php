<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Для неавторизованных пользователей - обход проверки сессии
global $USER;
if (!$USER->IsAuthorized() && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Принудительно валидируем форму для неавторизованных
    if (!empty($_POST['web_form_submit']) && !empty($_POST['WEB_FORM_ID'])) {
        $_SESSION['FORM_SUBMIT_' . $_POST['WEB_FORM_ID']] = true;
    }
}
?>

<div class="modal" id="modal-feedback">
    <button class="modal__close" aria-label="Закрыть" data-fancybox-close>
        <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M42.512 13.0119C43.4721 12.0519 45.0282 12.0519 45.9883 13.0119C46.9483 13.9719 46.9483 15.5281 45.9883 16.4881L16.4883 45.9881C15.5282 46.9482 13.9721 46.9482 13.012 45.9881C12.052 45.0281 12.052 43.4719 13.012 42.5119L42.512 13.0119Z" fill="currentColor" />
            <path d="M16.488 13.0119C15.5279 12.0519 13.9718 12.0519 13.0117 13.0119C12.0517 13.9719 12.0517 15.5281 13.0117 16.4881L42.5117 45.9881C43.4718 46.9482 45.0279 46.9482 45.988 45.9881C46.948 45.0281 46.948 43.4719 45.988 42.5119L16.488 13.0119Z" fill="currentColor" />
        </svg>
    </button>
    <div class="modal__feedback">
        <div class="modal__feedback-head">
            <h2 class="modal__feedback-title"><?=htmlspecialchars($arParams['MODAL_TITLE'] ?: 'Что нам важно знать')?></h2>
            <p class="modal__feedback-text"><?=htmlspecialchars($arParams['MODAL_TEXT'] ?: 'Давайте начнем обсуждение нашего совместного проекта')?></p>
            <ul class="modal__feedback-contacts">
                <li>
                    <a href="<?=htmlspecialchars($arParams['WHATSAPP_LINK'] ?: 'https://wa.me/79675553444?text=')?>" target="_blank">
                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.5005 2.2937C16.5691 2.2937 20.6778 6.40236 20.6778 11.471C20.6778 16.5396 16.5691 20.6482 11.5005 20.6482C9.87867 20.651 8.28534 20.2218 6.88435 19.4047L2.32693 20.6482L3.56769 16.089C2.74996 14.6875 2.32041 13.0935 2.32326 11.471C2.32326 6.40236 6.43192 2.2937 11.5005 2.2937ZM8.3729 7.15765L8.18936 7.16499C8.07069 7.17317 7.95474 7.20434 7.84797 7.25676C7.74846 7.31321 7.65759 7.38368 7.57815 7.466C7.46803 7.56971 7.40562 7.65964 7.33863 7.74683C6.99918 8.18817 6.81642 8.73 6.81919 9.28677C6.82103 9.73646 6.9385 10.1742 7.12204 10.5835C7.49739 11.4113 8.11502 12.2877 8.92996 13.0999C9.12636 13.2954 9.31908 13.4918 9.52649 13.6744C10.5391 14.5659 11.7458 15.2088 13.0506 15.5521L13.5718 15.6319C13.7416 15.6411 13.9114 15.6283 14.0821 15.62C14.3493 15.6059 14.6102 15.5336 14.8465 15.408C14.9666 15.3459 15.0839 15.2785 15.198 15.2061C15.198 15.2061 15.2369 15.1798 15.3127 15.1235C15.4366 15.0317 15.5128 14.9666 15.6156 14.8592C15.6927 14.7797 15.7569 14.6873 15.8083 14.5821C15.8799 14.4325 15.9515 14.147 15.9809 13.9094C16.0029 13.7276 15.9965 13.6285 15.9937 13.567C15.99 13.4689 15.9084 13.367 15.8193 13.3238L15.2852 13.0843C15.2852 13.0843 14.4868 12.7365 13.9986 12.5144C13.9475 12.4922 13.8927 12.4794 13.837 12.4768C13.7742 12.4702 13.7108 12.4772 13.6509 12.4973C13.5911 12.5174 13.5362 12.5502 13.4901 12.5933C13.4856 12.5915 13.4241 12.6438 12.7606 13.4477C12.7225 13.4989 12.67 13.5376 12.6099 13.5588C12.5497 13.5801 12.4846 13.5829 12.4228 13.567C12.363 13.5511 12.3044 13.5309 12.2475 13.5065C12.1337 13.4588 12.0943 13.4404 12.0163 13.4074C11.4894 13.1778 11.0017 12.8673 10.5709 12.4869C10.4552 12.3859 10.3479 12.2758 10.2377 12.1693C9.8767 11.8236 9.56205 11.4324 9.30164 11.0057L9.2475 10.9185C9.20919 10.8596 9.17779 10.7964 9.15389 10.7304C9.11902 10.5954 9.20987 10.4872 9.20987 10.4872C9.20987 10.4872 9.43288 10.243 9.53658 10.1109C9.63753 9.98241 9.72288 9.8576 9.77794 9.76858C9.88623 9.59421 9.92019 9.41525 9.86329 9.27668C9.60633 8.64895 9.3408 8.02459 9.0667 7.4036C9.01256 7.28062 8.85196 7.19252 8.70604 7.17509C8.65648 7.16897 8.60692 7.16407 8.55737 7.1604C8.43414 7.15333 8.31058 7.15456 8.18752 7.16407L8.3729 7.15765Z" fill="currentColor" />
                        </svg>
                    </a>
                    <a href="<?=htmlspecialchars($arParams['TELEGRAM_LINK'] ?: 'https://t.me/+79675553444')?>" target="_blank">
                        <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg" >
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4492 18.6201V18.6182L17.4658 18.5788L20.218 4.70277V4.65872C20.218 4.31274 20.0895 4.01081 19.8124 3.83002C19.5692 3.67125 19.2893 3.66024 19.0929 3.67492C18.9101 3.6916 18.7297 3.72853 18.5551 3.78505C18.4806 3.80882 18.4071 3.83577 18.3348 3.86581L18.3229 3.8704L2.97853 9.88976L2.97394 9.89159C2.92693 9.90655 2.8812 9.92527 2.8372 9.94758C2.72813 9.99673 2.62344 10.0551 2.52426 10.1219C2.32695 10.2578 1.9516 10.5771 2.01492 11.0837C2.06723 11.504 2.35631 11.7702 2.55179 11.9088C2.66691 11.99 2.79101 12.0578 2.92163 12.1107L2.951 12.1235L2.96018 12.1263L2.9666 12.129L5.65187 13.033C5.64208 13.2018 5.65952 13.3716 5.70418 13.5423L7.04865 18.6439C7.12209 18.922 7.2808 19.1701 7.50248 19.3534C7.72416 19.5367 7.99768 19.6459 8.28462 19.6657C8.57156 19.6856 8.8575 19.615 9.1023 19.464C9.3471 19.313 9.53845 19.0891 9.64948 18.8238L11.7492 16.5791L15.355 19.3433L15.4064 19.3653C15.734 19.5084 16.0396 19.5534 16.3195 19.5158C16.5994 19.4772 16.8215 19.3598 16.9885 19.2267C17.1818 19.0701 17.3358 18.8706 17.4382 18.6439L17.4456 18.6283L17.4483 18.6228L17.4492 18.6201ZM7.03488 13.1917C7.01999 13.1351 7.02353 13.0753 7.04498 13.0208C7.06644 12.9663 7.10469 12.9201 7.15418 12.8889L16.2589 7.10721C16.2589 7.10721 16.7949 6.78142 16.7756 7.10721C16.7756 7.10721 16.8711 7.16411 16.5838 7.43117C16.3122 7.68538 10.0946 13.6882 9.46502 14.2958C9.43085 14.3304 9.40647 14.3735 9.39435 14.4206L8.37935 18.2934L7.03488 13.1917Z" fill="currentColor" />
                        </svg>
                    </a>
                    <a href="tel:<?=htmlspecialchars($arParams['PHONE_NUMBER'] ?: '+79675553444')?>"><?=htmlspecialchars($arParams['PHONE_DISPLAY'] ?: '+7 (967) 555-34-44')?></a>
                </li>
                <li>
                    <a href="mailto:<?=htmlspecialchars($arParams['EMAIL'] ?: 'info@3d-group.space')?>"><?=htmlspecialchars($arParams['EMAIL'] ?: 'info@3d-group.space')?></a>
                </li>
            </ul>
        </div>
        
        <?php if ($arResult["isFormErrors"] == "Y"): ?>
            <div class="feedback__errors" style="background: #fee; border: 1px solid #fcc; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                <?=$arResult["FORM_ERRORS_TEXT"]?>
            </div>
        <?php endif; ?>
        
        <?php if ($arResult["isFormNote"] != "Y"): ?>
        
        <div class="modal__feedback-body">
            <form class="modal__feedback-form js-validate" 
                  action="<?=POST_FORM_ACTION_URI?>" 
                  method="POST" 
                  enctype="multipart/form-data"
                  autocomplete="off">
                
                <?=bitrix_sessid_post()?>
                <input type="hidden" name="web_form_submit" value="Y">
                <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult['arForm']['ID']?>">
                
                <div class="row">
                    <div class="col-lg-6">
                        <?php 
                        // Поле NAME
                        if (isset($arResult["QUESTIONS"]["NAME"])) {
                            $fieldData = $arResult["QUESTIONS"]["NAME"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["NAME"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input" name="<?=$fieldName?>" placeholder="Как к вам обращаться"<?=$required?> type="text">
                            <label class="ui-placeholder__label">Как к вам обращаться <?php if($arResult["QUESTIONS"]["NAME"]["REQUIRED"] == "Y"):?><sup>*</sup><?php endif;?>
                            </label>
                        </div>
                        <?php } ?>
                        
                        <?php 
                        // Поле PHONE
                        if (isset($arResult["QUESTIONS"]["PHONE"])) {
                            $fieldData = $arResult["QUESTIONS"]["PHONE"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["PHONE"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input js-mask-tel" name="<?=$fieldName?>" placeholder="Телефон"<?=$required?> type="tel">
                            <label class="ui-placeholder__label">Телефон <?php if($arResult["QUESTIONS"]["PHONE"]["REQUIRED"] == "Y"):?><sup>*</sup><?php endif;?>
                            </label>
                        </div>
                        <?php } ?>
                        
                        <?php 
                        // Поле EMAIL
                        if (isset($arResult["QUESTIONS"]["EMAIL"])) {
                            $fieldData = $arResult["QUESTIONS"]["EMAIL"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["EMAIL"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input" name="<?=$fieldName?>" placeholder="E-mail"<?=$required?> type="email">
                            <label class="ui-placeholder__label">E-mail <small>(не для рассылок)</small>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                        // Поле COMPANY
                        if (isset($arResult["QUESTIONS"]["COMPANY"])) {
                            $fieldData = $arResult["QUESTIONS"]["COMPANY"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["COMPANY"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input" name="<?=$fieldName?>" placeholder="Компания"<?=$required?> type="text">
                            <label class="ui-placeholder__label">Компания</label>
                        </div>
                        <?php } ?>
                        
                        <?php 
                        // Поле JOB
                        if (isset($arResult["QUESTIONS"]["JOB"])) {
                            $fieldData = $arResult["QUESTIONS"]["JOB"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["JOB"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input" name="<?=$fieldName?>" placeholder="Уточните вашу должность"<?=$required?> type="text">
                            <label class="ui-placeholder__label">Уточните вашу должность</label>
                        </div>
                        <?php } ?>
                        
                        <?php 
                        // Поле WEBSITE
                        if (isset($arResult["QUESTIONS"]["WEBSITE"])) {
                            $fieldData = $arResult["QUESTIONS"]["WEBSITE"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["WEBSITE"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <input class="ui-placeholder__input" name="<?=$fieldName?>" placeholder="Сайт"<?=$required?> type="text">
                            <label class="ui-placeholder__label">Сайт <small>(если есть)</small>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6 push-lg-6">
                        <?php 
                        // Поле MESSAGE
                        if (isset($arResult["QUESTIONS"]["MESSAGE"])) {
                            $fieldData = $arResult["QUESTIONS"]["MESSAGE"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                            $required = $arResult["QUESTIONS"]["MESSAGE"]["REQUIRED"] == "Y" ? ' required' : '';
                        ?>
                        <div class="ui-placeholder">
                            <textarea class="ui-placeholder__textarea" name="<?=$fieldName?>" placeholder="Ваши пожелания"<?=$required?>></textarea>
                            <label class="ui-placeholder__label">Ваши пожелания</label>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6 pull-lg-6">
                        <?php 
                        // Поле FILE
                        if (isset($arResult["QUESTIONS"]["FILE"])) {
                            $fieldData = $arResult["QUESTIONS"]["FILE"]["STRUCTURE"][0];
                            $fieldName = "form_" . $fieldData["FIELD_TYPE"] . "_" . $fieldData["ID"];
                        ?>
                        <label class="ui-file">
                            <input class="ui-file__input" name="<?=$fieldName?>" type="file">
                            <span class="ui-file__btn">Прикрепить файл<svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.75 11.25L11.25 15.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18 14.625L20.25 12.375C21.8033 10.8217 21.8033 8.3033 20.25 6.75V6.75C18.6967 5.1967 16.1783 5.1967 14.625 6.75L12.375 9M9 12.375L6.75 14.625C5.1967 16.1783 5.1967 18.6967 6.75 20.25V20.25C8.3033 21.8033 10.8217 21.8033 12.375 20.25L14.625 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </span>
                            <span class="ui-file__name"></span>
                        </label>
                        <?php } ?>
                        <div class="ui-caption">
                            <p>Прикрепить файл: JPG, PNG, PDF, DOC (X), XLS (X), ppt(X)</p>
                            <p>Максимальный размер файла 20 Мб</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ui-action">
                            <label class="ui-check">
                                <input class="ui-check__input" name="agree" required type="checkbox">
                                <span class="ui-check__checkbox"></span>
                                <span class="ui-check__text">Согласен и ознакомлен c <a href="<?=htmlspecialchars($arParams['PRIVACY_URL'] ?: '#')?>">политикой конфиденциальности</a> и обработкой персональных данных
                                </span>
                            </label>
                            <button class="ui-btn ui-btn--dark" type="submit"><?=htmlspecialchars($arParams['BUTTON_TEXT'] ?: 'отправить')?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <?php else: ?>
            <div class="feedback__success" style="background: #efe; border: 1px solid #cfc; padding: 30px; margin-top: 20px; text-align: center; border-radius: 5px;">
                <p style="color: #060; font-size: 18px; margin: 0;"><?=$arResult["FORM_NOTE"]?></p>
            </div>
        <?php endif; ?>
        
    </div>
</div>