<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<div id="<?= $pluginContainer; ?>" class="sberbank message fail">
    <div class="logo"></div>
    <p>К сожалению, при попытке оплаты вашего заказа <strong>#<?= $response->OrderNumber; ?></strong> на сумму <strong><?= (intval($response->Amount) / 100); ?> руб.</strong> произошла ошибка (код <?= $errorCode; ?>): "<?= $errorMessage; ?>". Попробуйте повторить оплату позже или свяжитесь с администратором сайта с указанием номера заказа.</p>
    <p><a class="return-back" href="<?= $returnUrl; ?>">Вернуться назад</a></p>
</div>
