<?php

use \mmaurice\sberpay\classes\Plugin;

global $sberbankPlugin;

require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

$sberbankPlugin = new Plugin(array(
    'colorTheme' => $colorTheme,
    'productMerchant' => $productMerchant,
    'productToken' => $productToken,
    'testMerchant' => $testMerchant,
    'testToken' => $testToken,
    'payButtonToken' => $payButtonToken,
    'mode' => $mode,
    'type' => $type,
    'restUriTest' => $restUriTest,
    'restUriProduction' => $restUriProduction,
    'shopkeeper' => $shopkeeper,
    'urlProcessing' => $urlProcessing,
    'urlSuccess' => $urlSuccess,
    'urlFail' => $urlFail,
    'urlProcessingExternal' => $urlProcessingExternal,
    'urlSuccessExternal' => $urlSuccessExternal,
    'urlFailExternal' => $urlFailExternal,
    'pageHandler' => $pageHandler,
    'queryLog' => $queryLog,
    'queryLogPath' => $queryLogPath,
    'minAmount' => $minAmount,
    'buttonCaption' => $buttonCaption,
    'currencyCaption' => $currencyCaption,
    'currencyShow' => $currencyShow,
    'placeholder' => $placeholder,
    'autoOrderId' => $autoOrderId,
    'debugMode' => $debugMode,
));

$sberbankPlugin->run();
