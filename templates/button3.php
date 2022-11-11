<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<form id="<?= $pluginContainer; ?>" class="sberbank form <?= $placeholder; ?>" method="post" action="<?= $action; ?>">
    <input type="hidden" name="orderNumber" value="<?= $orderNumber; ?>" />
    <input type="hidden" name="pageTitle" value="<?= $document['pagetitle']; ?>" />
    <input type="hidden" name="pageId" value="<?= $id; ?>" />
    <input type="hidden" name="handler" value="<?= $handler; ?>" />
    <input type="hidden" name="amount" value="<?= $amount; ?>" />
    <input type="hidden" name="type" value="<?= $type; ?>" />
<?php if (is_array($userdata) and !empty($userdata)) : ?>
    <?php foreach ($userdata as $userdataKey => $userdataItem) : ?>
    <input type="hidden" name="userdata[<?= $userdataKey; ?>]" value="<?= $userdataItem; ?>" />
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($currencyShow) : ?>
    <?php $modx->regClientStartupHTMLBlock("<style>.sberbank.form .price-fade:after { content: '{$currencyCaption}'; }</style>"); ?>
    <div class="price-fade"><?= $amount; ?></div>
<?php endif; ?>
    <input type="submit" value="<?= $buttonCaption; ?>" />
</form>

<script>
    var formContainer = 'form#<?= $pluginContainer; ?>';
</script>

<?php if ($autoAmount) : ?>
    <?php $modx->regClientStartupScript($pluginWebRootPath . '/assets/scripts/script.js?v=' . time()); ?>
<?php endif; ?>
