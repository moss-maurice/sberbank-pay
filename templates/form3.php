<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<form id="<?= $pluginContainer; ?>" class="sberbank form <?= $placeholder; ?>" method="post" action="<?= $action; ?>">
    <input type="hidden" name="orderNumber" value="<?= $orderNumber; ?>" />
    <input type="hidden" name="orderBundle" value="<?= $orderBundle; ?>" />
    <input type="hidden" name="pageTitle" value="<?= $document['pagetitle']; ?>" />
    <input type="hidden" name="pageId" value="<?= $id; ?>" />
    <input type="hidden" name="handler" value="<?= $handler; ?>" />
    <input type="hidden" name="type" value="<?= $type; ?>" />
<?php if (is_array($userdata) and !empty($userdata)) : ?>
    <?php foreach ($userdata as $userdataKey => $userdataItem) : ?>
    <input type="hidden" name="userdata[<?= $userdataKey; ?>]" value="<?= $userdataItem; ?>" />
    <?php endforeach; ?>
<?php endif; ?>
    <input type="hidden" name="userdata[amount]" value="<?= $amount; ?>" />
<?php if ($currencyShow) : ?>
    <?php $modx->regClientStartupHTMLBlock("<style>.sberbank.form .back-place:after { content: '{$currencyCaption}'; }</style>"); ?>
    <input type="text" name="phone" value="<?= $phone; ?>" required placeholder="Номер телефона" />
    <input type="text" name="email" value="<?= $email; ?>" required placeholder="Электронный адрес" />
    <!-- Пример реализации дополнительных полей, которые передаются в качестве jsonParams -->
    <input type="text" name="params[order]" value="" required placeholder="Номер заказа" />
    <div class="price back-place">
        <input type="text" name="amount" value="<?= $amount; ?>" required placeholder="Размер оплаты" />
    </div>
<?php endif; ?>
    <input type="submit" value="<?= $buttonCaption; ?>" />
</form>

<script>
    var formContainer = 'form#<?= $pluginContainer; ?>';
</script>

<?php if ($autoAmount) : ?>
    <?php $modx->regClientStartupScript($pluginWebRootPath . '/assets/scripts/script.js?v=' . time()); ?>
<?php endif; ?>
