<?php global $modx; ?>

<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/colors/' . $colorTheme . '.css?v=' . time()); ?>
<?php $modx->regClientCSS($pluginWebRootPath . '/assets/styles/style.css?v=' . time()); ?>

<div id="<?= $pluginContainer; ?>" class="sberbank">
    <a target="_blank" href="<?= $domainRestUri; ?>/payment/docsite/payform-1.html?token=<?= $payButtonToken; ?>&ask=description&ask=email&ask=%7B%22name%22:%22%D0%A4%D0%98%D0%9E%22,%22placeholder%22:%22%22,%22label%22:%22%D0%A4%D0%98%D0%9E%22%7D&ask=%7B%22name%22:%22%D0%A2%D0%B5%D0%BB%D0%B5%D1%84%D0%BE%D0%BD%22,%22placeholder%22:%22%22,%22label%22:%22%D0%A2%D0%B5%D0%BB%D0%B5%D1%84%D0%BE%D0%BD%22%7D&ask=%7B%22name%22:%22%D0%9D%D0%BE%D0%BC%D0%B5%D1%80_%D0%B4%D0%BE%D0%B3%D0%BE%D0%B2%D0%BE%D1%80%D0%B0%22,%22placeholder%22:%22%22,%22label%22:%22%D0%9D%D0%BE%D0%BC%D0%B5%D1%80%20%D0%B4%D0%BE%D0%B3%D0%BE%D0%B2%D0%BE%D1%80%D0%B0%22%7D">Перейти к оплате</a>
</div>

<script>
    var formContainer = 'form#<?= $pluginContainer; ?>';
</script>

<?php if ($autoAmount) : ?>
    <?php $modx->regClientStartupScript($pluginWebRootPath . '/assets/scripts/script.js?v=' . time()); ?>
<?php endif; ?>
