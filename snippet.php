<?php

use \mmaurice\sberpay\classes\Plugin;

global $sberbankPlugin;

require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

$sberbankPlugin->runSnippet(array(
    'colorTheme' => $colorTheme,
    'mode' => $mode,
    'type' => $type,
    'minAmount' => $minAmount,
    'buttonCaption' => $buttonCaption,
    'currencyCaption' => $currencyCaption,
    'currencyShow' => $currencyShow,
    'placeholder' => $placeholder,
    'autoOrderId' => $autoOrderId,
    'debugMode' => $debugMode,
    'action' => $action,
));
