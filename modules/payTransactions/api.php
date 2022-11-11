<?php

namespace module;

require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

include_once realpath(dirname(__FILE__) . '/src/configs/config.php');

header('Content-type: application/json; charset=utf-8');

$className = ucfirst(trim($_POST['tabName']));
$method = 'action' . ucfirst(trim($_POST['method']));
$fullClassName = '\\' . __NAMESPACE__ . '\\tabs\\' . $className;

$tabClass = new $fullClassName;

if (method_exists($tabClass, $method)) {
    echo json_encode(call_user_func_array([$tabClass, $method], array()));
} else {
    echo json_encode(call_user_func_array([$tabClass, 'actionIndex'], array()));
}

die();
