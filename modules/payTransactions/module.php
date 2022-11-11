<?php

namespace module;

use \DocumentParser;

require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

include_once realpath(dirname(__FILE__) . '/src/configs/config.php');

global $modx;

if ((IN_MANAGER_MODE != 'true') or empty($modx) or !($modx instanceof DocumentParser)) {
    die('Please use the MODX Content Manager instead of accessing this file directly.');
}

if (!$modx->hasPermission('exec_module')) {
    $modx->sendRedirect('index.php');
}

if (!is_array($modx->event->params)) {
    $modx->event->params = [];
}

preg_match_all('/([^\=\&]+)\=([^\&$]+)/i', str_replace('&amp;', '&', trim($_SERVER['QUERY_STRING'])), $matches);
$request = array_combine($matches[1], $matches[2]);

$tabs = [];

foreach (glob(realpath(dirname(__FILE__) . '/src/tabs/') . '/*.php') as $index => $fileName) {
    $className = pathinfo($fileName)['filename'];
    $tabsIName = lcfirst($className);
    $fullClassName = '\\' . __NAMESPACE__ . '\\tabs\\' . $className;

    $tabs[$tabsIName] = new $fullClassName;

    if (array_key_exists('tabName', $request)) {
        if ($request['tabName'] === $tabsIName) {
            setcookie('webfxtab_documentPane', $index);
        }
    } else {
        $request['tabName'] = $tabsIName;
        setcookie('webfxtab_documentPane', $index);
    }
}

uasort($tabs, function ($left, $right) {
    return ((intval($left->orderPosition) <= intval($right->orderPosition)) ? ((intval($left->orderPosition) < intval($right->orderPosition)) ? -1 : 0) : 1);
});

$modulePath = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . '/src/'))), '/');

include_once MODX_MANAGER_PATH . 'includes/header.inc.php';

?>

<link rel="stylesheet" type="text/css" href="/admin/media/style/default/css/styles.min.css?v=<?= time(); ?>" />
<link rel="stylesheet" type="text/css" href="<?= $modulePath; ?>/assets/styles/style.css?v=<?= time(); ?>" />
<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-grid.min.css">
<link rel="stylesheet" type="text/css" href="/template/css/bootstrap-spacing.min.css">
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<h1>
    <i class="fa fa-ruble-sign"></i> Оплаты с сайта
</h1>

<form name="settings" method="post" id="mutate" class="modx-evo-lk-admin">
    <div class="sectionBody" id="settingsPane">
        <div class="tab-pane" id="documentPane">
            <script type="text/javascript">
                var tpSettings = new WebFXTabPane(document.getElementById('documentPane'), <?= ($modx->getConfig('remember_last_tab') == 1 ? 'true' : 'false') ?>);
            </script>

            <?php foreach ($tabs as $name => $tab) : ?>
                <div class="tab-page" id="tab_<?= $name ?>">
                    <h2 class="tab"><?= $tab->title ?></h2>
                    <script type="text/javascript">
                        tpSettings.addTabPage(document.getElementById('tab_<?= $name ?>'));
                    </script>
                </div>
                <?php $tabIndex++; ?>
            <?php endforeach; ?>
        </div>
    </div>
</form>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?= $modulePath . '/assets/scripts/buffer.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/scripts/module.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/scripts/tabs/transactionsTabScripts.class.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>
<script src="<?= $modulePath . '/assets/scripts/script.js'; ?>?v=<?= time(); ?>" type="text/javascript"></script>

<?php include_once MODX_MANAGER_PATH . 'includes/footer.inc.php'; ?>
