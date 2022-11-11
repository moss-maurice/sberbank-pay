<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<div id="<?= $pluginContainer; ?>" class="sberbank message error">
    <div class="logo"></div>
    <p>Что-то пошло не так. Попробуйте повторить оплату позже или свяжитесь с администратором сайта.</p>
    <p><a class="return-back" href="<?= $returnUrl; ?>">Вернуться назад</a></p>
</div>
