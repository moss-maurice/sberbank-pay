<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<div id="<?= $pluginContainer; ?>" class="sberbank message success">
    <div class="logo"></div>
    <p>Заказ <strong>#<?= $orderNumber; ?></strong> успешно оплачен на сумму <strong><?= $amount; ?> руб.</strong>.</p>
    <p><a class="return-back" href="<?= $returnUrl; ?>">Вернуться назад</a></p>
</div>
