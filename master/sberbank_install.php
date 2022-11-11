<?php

class Installer
{
    protected static $pluginEvents = [
        92 => 'OnParseDocument',
        1000 => 'OnPageNotFound',
    ];

    protected static $pluginConfig = [
        "colorTheme" => [
            [
                "label" => "Цветовая схема",
                "type" => "list",
                "value" => "default",
                "options" => "default,custom",
                "default" => "default",
                "desc" => "",
            ],
        ],
        "placeholder" => [
            [
                "label" => "Плейсхолдер формы",
                "type" => "string",
                "value" => "payButton",
                "default" => "payButton",
                "desc" => "",
            ],
        ],
        "mode" => [
            [
                "label" => "Режим работы плагина",
                "type" => "list",
                "value" => "product",
                "options" => "test,product,button",
                "default" => "test",
                "desc" => "",
            ],
        ],
        "type" => [
            [
                "label" => "Тип оплаты",
                "type" => "list",
                "value" => "onestage",
                "options" => "onestage,twostage",
                "default" => "onestage",
                "desc" => "",
            ],
        ],
        "productMerchant" => [
            [
                "label" => "Боевой API-мерчант",
                "type" => "string",
                "value" => "production-api-login",
                "default" => "production-api-login",
                "desc" => "",
            ],
        ],
        "productToken" => [
            [
                "label" => "Боевой API-токен",
                "type" => "string",
                "value" => "production-api-password",
                "default" => "production-api-password",
                "desc" => "",
            ],
        ],
        "testMerchant" => [
            [
                "label" => "Тестовый API-мерчант",
                "type" => "string",
                "value" => "test-api-login",
                "default" => "test-api-login",
                "desc" => "",
            ],
        ],
        "testToken" => [
            [
                "label" => "Тестовый API-токен",
                "type" => "string",
                "value" => "test-api-password",
                "default" => "test-api-password",
                "desc" => "",
            ],
        ],
        "payButtonToken" => [
            [
                "label" => "Токен платежной кнопки",
                "type" => "string",
                "value" => "pay-button-token",
                "default" => "pay-button-token",
                "desc" => "",
            ],
        ],
        "restUriTest" => [
            [
                "label" => "Путь до тестового API-сервера",
                "type" => "string",
                "value" => "https://3dsec.sberbank.ru/payment/rest/",
                "default" => "https://3dsec.sberbank.ru/payment/rest/",
                "desc" => "",
            ],
        ],
        "restUriProduction" => [
            [
                "label" => "Путь до боевого API-сервера",
                "type" => "string",
                "value" => "https://securepayments.sberbank.ru/payment/rest/",
                "default" => "https://securepayments.sberbank.ru/payment/rest/",
                "desc" => "",
            ],
        ],
        "shopkeeper" => [
            [
                "label" => "Использование совместно с ShopKeeper",
                "type" => "list",
                "value" => "no",
                "options" => "yes,no",
                "default" => "no",
                "desc" => "",
            ],
        ],
        "urlProcessing" => [
            [
                "label" => "Путь к странице обработки",
                "type" => "string",
                "value" => "/pay/processing",
                "default" => "/pay/form",
                "desc" => "",
            ],
        ],
        "urlSuccess" => [
            [
                "label" => "Путь к странице успешной оплаты",
                "type" => "string",
                "value" => "/pay/success",
                "default" => "/pay/success",
                "desc" => "",
            ],
        ],
        "urlFail" => [
            [
                "label" => "Путь к странице не успешной оплаты",
                "type" => "string",
                "value" => "/pay/fail",
                "default" => "/pay/fail",
                "desc" => "",
            ],
        ],
        "urlProcessingExternal" => [
            [
                "label" => "Передать странцу обработки внешнему модулю",
                "type" => "list",
                "value" => "off",
                "options" => "on,off",
                "default" => "off",
                "desc" => "",
            ],
        ],
        "urlSuccessExternal" => [
            [
                "label" => "Передать странцу успешной оплаты внешнему модулю",
                "type" => "list",
                "value" => "off",
                "options" => "on,off",
                "default" => "off",
                "desc" => "",
            ],
        ],
        "urlFailExternal" => [
            [
                "label" => "Передать странцу не успешной оплаты внешнему модулю",
                "type" => "list",
                "value" => "off",
                "options" => "on,off",
                "default" => "off",
                "desc" => "",
            ],
        ],
        "pageHandler" => [
            [
                "label" => "ID страницы,отрабатывающей запросы",
                "type" => "int",
                "value" => "1",
                "default" => "1",
                "desc" => "",
            ],
        ],
        "queryLog" => [
            [
                "label" => "Режим логирования запросов",
                "type" => "list",
                "value" => "off",
                "options" => "on,off",
                "default" => "off",
                "desc" => "",
            ],
        ],
        "queryLogPath" => [
            [
                "label" => "Путь хранения файлов логов запросов",
                "type" => "string",
                "value" => "~/assets/plugins/sberbank/logs",
                "default" => "~/assets/plugins/sberbank/logs",
                "desc" => "",
            ],
        ],
        "minAmount" => [
            [
                "label" => "Минимальный размер оплаты",
                "type" => "float",
                "value" => "100",
                "default" => "100",
                "desc" => "",
            ],
        ],
        "buttonCaption" => [
            [
                "label" => "Текст подписи кнопки оплаты",
                "type" => "string",
                "value" => "Оплатить",
                "default" => "Оплатить",
                "desc" => "",
            ],
        ],
        "currencyCaption" => [
            [
                "label" => "Подпись валюты оплаты",
                "type" => "string",
                "value" => "руб.",
                "default" => "руб.",
                "desc" => "",
            ],
        ],
        "currencyShow" => [
            [
                "label" => "Режим вывода подписи валюты",
                "type" => "list",
                "value" => "on",
                "options" => "on,off",
                "default" => "on",
                "desc" => "",
            ],
        ],
        "autoOrderId" => [
            [
                "label" => "Автоматическая генерация номера заказа<br /><small>(для работы на страницах с включенным кешированием)</small>",
                "type" => "list",
                "value" => "on",
                "options" => "on,off",
                "default" => "on",
                "desc" => "",
            ],
        ],
        "debugMode" => [
            [
                "label" => "Режим отладки",
                "type" => "list",
                "value" => "off",
                "options" => "on,off",
                "default" => "off",
                "desc" => "",
            ],
        ],
    ];

    protected static function modxConnect($path = "/index.php")
    {
        if (!defined('MODX_API_MODE')) {
            define('MODX_API_MODE', true);
        }

        if (!defined('MODX_BASE_PATH')) {
            define('MODX_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
        }

        if (!defined('MODX_BASE_URL')) {
            define('MODX_BASE_URL', "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/");
        }

        if (!defined('MODX_SITE_URL')) {
            define('MODX_BASE_URL', "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/");
        }

        global $modx;
        global $database_type;
        global $database_server;
        global $database_user;
        global $database_password;
        global $database_connection_charset;
        global $database_connection_method;
        global $dbase;
        global $table_prefix;
        global $base_url;
        global $base_path;

        if (isset($modx) and !empty($modx)) {
            return $modx;
        }

        @include_once(realpath($_SERVER['DOCUMENT_ROOT'] . '/index.php'));

        $modx->db->connect();

        if (empty($modx->config)) {
            $modx->getSettings();
        }

        return $modx;
    }

    public static function getList(array $filter = [])
    {
        static::modxConnect();

        global $modx;

        $resource = static::search($filter, $log);

        if (!is_null($resource) and $modx->db->getRecordCount($resource)) {
            $results = [];

            while ($row = $modx->db->getRow($resource)) {
                $results[] = $row;
            }

            return $results;
        }

        return null;
    }

    public function getItem(array $filter = [])
    {
        static::modxConnect();

        global $modx;

        $resource = static::search($filter, $log);

        if (!is_null($resource) and $modx->db->getRecordCount($resource)) {
            return $modx->db->getRow($resource);
        }

        return null;
    }

    protected static function search(array $filter = [])
    {
        static::modxConnect();

        global $modx;

        $query = static::getRawSql($filter);

        if (!is_null($query)) {
            return $modx->db->query($query);
        }

        return null;
    }

    protected function getRawSql(array $filter = [])
    {
        static::modxConnect();

        global $modx;

        if (!array_key_exists('alias', $filter)) {
            $filter['alias'] = '';
        }

        if (!array_key_exists('select', $filter) or is_null($filter['select']) or empty($filter['select'])) {
            $filter['select'] = (!empty($filter['alias']) ? "{$filter['alias']}." : '') . '*';
        }

        if (!is_array($filter['select'])) {
            $filter['select'] = [$filter['select']];
        }

        if (!array_key_exists('from', $filter) or empty($filter['from'])) {
            return null;
        } else {
            $filter['from'] = $modx->getFullTableName($filter['from']);
        }

        return "SELECT" . PHP_EOL
            . "\t" . implode("," . PHP_EOL . "\t", $filter['select']) . PHP_EOL
            . "FROM " . trim("{$filter['from']} {$filter['alias']}") . PHP_EOL
            . (!empty($filter['join']) ? implode(PHP_EOL, $filter['join']) . PHP_EOL : "")
            . (!empty($filter['where']) ? "WHERE" . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $filter['where']) . PHP_EOL : "")
            . (!empty($filter['group']) ? "GROUP BY" . PHP_EOL . "\t" . implode("," . PHP_EOL . "\t", $filter['group']) . PHP_EOL : "")
            . (!empty($filter['having']) ? "HAVING" . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $filter['having']) . PHP_EOL : "")
            . (!empty($filter['order']) ? "ORDER BY" . PHP_EOL . "\t" . implode("," . PHP_EOL . "\t", $filter['order']) . PHP_EOL : "")
            . (!empty($filter['limit']) ? "LIMIT " . intval($filter['limit']) . PHP_EOL : "")
            . (!empty($filter['offset']) ? "OFFSET " . intval($filter['offset']) . PHP_EOL : "");
    }

    public static function insert($table, array $fields)
    {
        static::modxConnect();

        global $modx;

        $fields = array_filter($fields);

        if (is_array($fields) and !empty($fields)) {
            $sql = "INSERT
                INTO " . $modx->getFullTableName($table) . "
                    (`" . implode("`, `", array_keys($fields)) . "`)
                VALUES
                    ('" . implode("', '", array_values($fields)) . "');";

            if ($modx->db->query($sql)) {
                return $modx->db->getInsertId();
            }
        }

        return null;
    }

    public static function getTemplates()
    {
        return static::getList([
            'select' => [
                'id',
                'templatename',
            ],
            'from' => 'site_templates',
            'order' => [
                'templatename',
            ],
        ]);
    }

    public static function getModes()
    {
        return [
            'test',
            'product',
            'button',
        ];
    }

    public static function addPage()
    {
        if (array_key_exists('page', $_POST) and is_array($_POST['page']) and !empty($_POST['page'])) {
            $_POST['page']['content'] = '{{' . $_POST['plugin']['placeholder'] . '}}';

            $fields = $_POST['page'];

            return static::insert('site_content', $fields);
        }

        return null;
    }

    public static function addPlugin($pageHandler)
    {
        if (array_key_exists('plugin', $_POST) and is_array($_POST['plugin']) and !empty($_POST['plugin'])) {
            $_POST['plugin']['pageHandler'] = $pageHandler;

            $pluginConfig = static::$pluginConfig;

            $pluginConfig['placeholder'][0]['value'] = $_POST['plugin']['placeholder'];
            $pluginConfig['mode'][0]['value'] = $_POST['plugin']['mode'];
            $pluginConfig['testMerchant'][0]['value'] = $_POST['plugin']['testMerchant'];
            $pluginConfig['testToken'][0]['value'] = $_POST['plugin']['testToken'];
            $pluginConfig['productMerchant'][0]['value'] = $_POST['plugin']['productMerchant'];
            $pluginConfig['productToken'][0]['value'] = $_POST['plugin']['productToken'];

            $json = json_encode($pluginConfig, JSON_UNESCAPED_UNICODE);

            $fields = [
                'name' => 'Sberbank Payment',
                'description' => '<strong>0.40.1</strong> Sberbank online payment for ModX Evo site',
                'plugincode' => '/*\r\n * Sberbank online payment for ModX Evo site\r\n * @version 0.40.1\r\n * @author Viktor Voronkov <kreexus@yandex.ru>\r\n */\r\n\r\nrequire(realpath($_SERVER[\\\'DOCUMENT_ROOT\\\'] . \\\'/assets/plugins/sberbank/plugin.php\\\'));\r\n',
                'properties' => $json,
                'createdon' => time(),
                'editedon' => time(),
            ];

            return static::insert('site_plugins', $fields);
        }

        return null;
    }

    public static function addPluginHooks($pluginId)
    {
        if ($pluginId and is_array(static::$pluginEvents) and !empty(static::$pluginEvents)) {
            foreach (static::$pluginEvents as $id => $name) {
                static::insert('site_plugin_events', [
                    'pluginid' => $pluginId,
                    'evtid' => $id,
                ]);
            }

            return true;
        }

        return false;
    }
}

$stage = !isset($_POST['next']) ? !isset($_POST['prev']) ? 0 : intval($_POST['prev_stage']) : intval($_POST['next_stage']);

if ($stage === 1) {
    $templatesList = Installer::getTemplates();
} else if ($stage === 2) {
    $pluginModes = Installer::getModes();
} else if ($stage === 3) {
    global $modx;

    shell_exec("composer create-project mmaurice/sberbank-pay ./assets/plugins/sberbank");

    $pageId = Installer::addPage();

    $pluginId = Installer::addPlugin($pageId);

    Installer::addPluginHooks($pluginId);

    $modx->clearCache('full');
} else if ($stage === 4) {
    unlink(realpath(__FILE__));

    $main = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/";

    header("Location: {$main}");

    echo "<script>window.location.replace('{$main}');</script>";
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Tabler comes with tons of well-designed components and features. Start your adventure with Tabler and make your dashboard great again. For free!" />

    <title>Wizard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>

    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta15/dist/css/tabler.min.css" />

    <style>
        
    </style>
</head>
<body class=" border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4"></div>
<?php if ($stage === 0) : ?>
            <form method="post" action="">
                <div class="card card-md">
                    <div class="card-body text-center py-4 p-sm-5">
                        <svg id="b59c9f89-c93e-413f-a14b-776784e615e2" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" height="120" viewBox="0 0 1075.51587 646.89913">
                            <title>sign_in</title>
                            <path d="M136.59282,733.55706l63.43134-91.13824L136.56059,743.4707l-.037,10.3692q-6.78516-.05706-13.43322-.44141c-.02019-2.35442,5.37117-131.89917,5.142-134.24281.3129-.0888.48846-12.252.6126-12.78462L62.57779,506.77732,128.91613,596.945l.1978,2.66552,3.93549-100.64793L76.0857,395.895,133.5,481.27779c.02132-1.3695,2.71745-208.02265,2.744-209.29776.01628.67732,1.25371,164.45521,1.26281,165.14538l54.47905-65.92288-54.53131,80.08836-.27852,90.07922,50.556-87.10756C124.63867,581.2954,138.60155,536.8418,136.99011,604.702l73.43568-121.29149L136.92724,622.17566Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <path d="M1106.98992,745.9302l26.4334-37.97954-26.44683,42.1108-.0154,4.3211q-2.82754-.02378-5.598-.18395c-.00841-.98114,2.2383-54.96562,2.14282-55.94228.13039-.037.20355-5.10569.25528-5.32766L1076.146,651.42551l27.64482,37.57509.08242,1.11079,1.64-41.94247-23.73818-42.95081,23.92592,35.58112c.00889-.57071,1.13243-86.68815,1.14349-87.21952.00678.28226.52245,68.53253.52625,68.82014l22.70275-27.47169L1107.349,628.303l-.11607,37.53823,21.06792-36.29986c-26.29252,52.9377-20.47384,34.4128-21.14537,62.69183l30.60245-50.54515-30.62865,57.82684Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <ellipse cx="536" cy="625.89913" rx="536" ry="21" fill="#d0cde1"/>
                            <rect x="184" y="248.03055" width="612" height="364.87" fill="#3f3d56"/>
                            <path d="M397.70209,374.581a146.35424,146.35424,0,0,1-146.32,143.97c-1.72,0-3.43-.03-5.14-.09V374.581Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M582.17908,739.45064H523.9723a31.00847,31.00847,0,0,1,58.20678,0Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M858.24207,550.72094v122.9a61.52336,61.52336,0,0,1,0-122.9Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <rect x="279.45927" y="380.6149" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="279.45927" y="451.67903" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="553.59711" y="522.74315" width="148.00427" height="36.06239" rx="18.03118" fill="#fff"/>
                            <path d="M955.29387,238.67007s16.82875,6.54451,17.76368,28.04791-4.67465,81.33893-4.67465,81.33893,20.56846,29.91777,4.67465,38.33214-17.76368-33.65749-17.76368-33.65749l-18.6986-88.81837S947.81443,237.73514,955.29387,238.67007Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M783.26671,307.85491s-40.202,26.178-42.07187,5.60958S753.34894,212.492,753.34894,212.492s1.86986-34.59242,21.50339-26.178-5.60958,37.39721-5.60958,37.39721l2.80479,65.44511,18.69861-.93493Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M847.77689,332.1631s-123.41079-45.81159-116.86628,7.47944,38.33214,120.606,38.33214,120.606l11.21917,17.76368h23.37325L788.87629,381.7144s53.291,29.91777,69.18484,25.24312c0,0,20.56846,137.43475,28.04791,147.719s20.56846,134.63,20.56846,134.63l12.15409,14.95888h23.37326V493.906s40.202-130.89023,9.3493-137.43475S847.77689,332.1631,847.77689,332.1631Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M777.65712,465.85812s-5.60958-10.28423-14.95888,0-31.78763,22.43833-31.78763,22.43833-48.61637,11.21916-9.3493,20.56847,43.94172,0,43.94172,0,6.54451,1.86986,12.15409,3.73972S819.729,507.93,819.729,502.3204s-12.37851-39.134-17.40842-34.05842S777.65712,465.85812,777.65712,465.85812Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M920.6231,695.01108s-13.7851-11.35244-17.02865-8.10888-20.2722,25.13753-20.2722,25.13753-45.40973,34.86818-4.05444,40.5444,38.92263-9.73066,38.92263-9.73066h12.9742c1.62178,0,23.51576-9.73066,22.70487-13.7851s-10.54155-37.11568-13.7851-34.7756S920.6231,695.01108,920.6231,695.01108Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <circle cx="733.17873" cy="44.80466" r="31.78763" fill="#a0616a"/>
                            <path d="M817.85913,167.61537s24.30818,20.56847,41.13693,20.56847S834.68787,226.516,834.68787,226.516l-36.46228-32.72256Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M827.20843,226.516l-.728-7.36568s18.4917-32.83632,24.10129-35.64112,9.3493-2.80479,9.3493-2.80479,18.6986-19.63353,39.26707-9.3493,69.18484,75.72935,69.18484,75.72935-34.59242,2.80479-29.91777,29.91777c0,0,34.59242,88.81838,25.24312,89.75331s-37.39721-5.60959-66.38,7.47944-42.07186,4.67465-42.07186,4.67465l-14.95889-48.61637s-28.04791-15.89382-27.113-29.91777L772.515,318.60661l3.73972-34.59242,24.77565-28.51537S796.35573,251.75909,827.20843,226.516Z" transform="translate(-62.24207 -126.55043)" fill="#575a89"/>
                            <path d="M766.18031,174.79171a11.73657,11.73657,0,0,1-4.616,3.14369c-1.81051.51062-4.09154-.11555-4.81307-1.85281l1.34411-1.74939-4.02944-2.5039,1.75581-2.06012-3.24556-3.5243,2.56163-2.03236-4.87719-3.48808c6.32111-10.31466,13.15729-20.75845,23.13705-27.59607s23.9246-9.26726,34.14813-2.7998c5.30792,3.35782,9.06128,8.68723,12.06785,14.20172,6.04656,11.09024,11.37634,21.9007,12.16329,34.50764-3.79816-3.69869-9.34319-5.783-13.14136-9.48173a28.463,28.463,0,0,0-5.66925-4.64065c-3.97269-2.24026-8.726-2.43845-13.17348-3.449-3.73952-.84974-6.16609-2.53534-8.99529-4.94505-3.36564-2.8666-3.50275-1.7582-6.90135,1.46926Q775.04391,166.39718,766.18031,174.79171Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                        </svg>
                        <h1 class="mt-5">Добро пожаловать в мастер установки</h1>
                        <p class="text-muted">Следуя шагам мастера, вы сможете установить програмный продукт</p>
                    </div>
                </div>
                <input type="hidden" name="prev_stage" value="0" />
                <input type="hidden" name="next_stage" value="1" />
                <div class="row align-items-center mt-3">
                    <div class="col-4">
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" aria-label="0% Complete">
                                <span class="visually-hidden">0% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="btn-list justify-content-end">
                            <input type="submit" name="next" class="btn btn-primary" value="Начать" />
                        </div>
                    </div>
                </div>
            </form>
<?php elseif ($stage === 1) : ?>
            <form method="post" action="">
                <div class="card card-md">
                    <div class="card-body text-center py-4 p-sm-5">
                        <svg id="b59c9f89-c93e-413f-a14b-776784e615e2" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" height="120" viewBox="0 0 1075.51587 646.89913">
                            <title>sign_in</title>
                            <path d="M136.59282,733.55706l63.43134-91.13824L136.56059,743.4707l-.037,10.3692q-6.78516-.05706-13.43322-.44141c-.02019-2.35442,5.37117-131.89917,5.142-134.24281.3129-.0888.48846-12.252.6126-12.78462L62.57779,506.77732,128.91613,596.945l.1978,2.66552,3.93549-100.64793L76.0857,395.895,133.5,481.27779c.02132-1.3695,2.71745-208.02265,2.744-209.29776.01628.67732,1.25371,164.45521,1.26281,165.14538l54.47905-65.92288-54.53131,80.08836-.27852,90.07922,50.556-87.10756C124.63867,581.2954,138.60155,536.8418,136.99011,604.702l73.43568-121.29149L136.92724,622.17566Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <path d="M1106.98992,745.9302l26.4334-37.97954-26.44683,42.1108-.0154,4.3211q-2.82754-.02378-5.598-.18395c-.00841-.98114,2.2383-54.96562,2.14282-55.94228.13039-.037.20355-5.10569.25528-5.32766L1076.146,651.42551l27.64482,37.57509.08242,1.11079,1.64-41.94247-23.73818-42.95081,23.92592,35.58112c.00889-.57071,1.13243-86.68815,1.14349-87.21952.00678.28226.52245,68.53253.52625,68.82014l22.70275-27.47169L1107.349,628.303l-.11607,37.53823,21.06792-36.29986c-26.29252,52.9377-20.47384,34.4128-21.14537,62.69183l30.60245-50.54515-30.62865,57.82684Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <ellipse cx="536" cy="625.89913" rx="536" ry="21" fill="#d0cde1"/>
                            <rect x="184" y="248.03055" width="612" height="364.87" fill="#3f3d56"/>
                            <path d="M397.70209,374.581a146.35424,146.35424,0,0,1-146.32,143.97c-1.72,0-3.43-.03-5.14-.09V374.581Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M582.17908,739.45064H523.9723a31.00847,31.00847,0,0,1,58.20678,0Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M858.24207,550.72094v122.9a61.52336,61.52336,0,0,1,0-122.9Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <rect x="279.45927" y="380.6149" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="279.45927" y="451.67903" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="553.59711" y="522.74315" width="148.00427" height="36.06239" rx="18.03118" fill="#fff"/>
                            <path d="M955.29387,238.67007s16.82875,6.54451,17.76368,28.04791-4.67465,81.33893-4.67465,81.33893,20.56846,29.91777,4.67465,38.33214-17.76368-33.65749-17.76368-33.65749l-18.6986-88.81837S947.81443,237.73514,955.29387,238.67007Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M783.26671,307.85491s-40.202,26.178-42.07187,5.60958S753.34894,212.492,753.34894,212.492s1.86986-34.59242,21.50339-26.178-5.60958,37.39721-5.60958,37.39721l2.80479,65.44511,18.69861-.93493Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M847.77689,332.1631s-123.41079-45.81159-116.86628,7.47944,38.33214,120.606,38.33214,120.606l11.21917,17.76368h23.37325L788.87629,381.7144s53.291,29.91777,69.18484,25.24312c0,0,20.56846,137.43475,28.04791,147.719s20.56846,134.63,20.56846,134.63l12.15409,14.95888h23.37326V493.906s40.202-130.89023,9.3493-137.43475S847.77689,332.1631,847.77689,332.1631Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M777.65712,465.85812s-5.60958-10.28423-14.95888,0-31.78763,22.43833-31.78763,22.43833-48.61637,11.21916-9.3493,20.56847,43.94172,0,43.94172,0,6.54451,1.86986,12.15409,3.73972S819.729,507.93,819.729,502.3204s-12.37851-39.134-17.40842-34.05842S777.65712,465.85812,777.65712,465.85812Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M920.6231,695.01108s-13.7851-11.35244-17.02865-8.10888-20.2722,25.13753-20.2722,25.13753-45.40973,34.86818-4.05444,40.5444,38.92263-9.73066,38.92263-9.73066h12.9742c1.62178,0,23.51576-9.73066,22.70487-13.7851s-10.54155-37.11568-13.7851-34.7756S920.6231,695.01108,920.6231,695.01108Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <circle cx="733.17873" cy="44.80466" r="31.78763" fill="#a0616a"/>
                            <path d="M817.85913,167.61537s24.30818,20.56847,41.13693,20.56847S834.68787,226.516,834.68787,226.516l-36.46228-32.72256Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M827.20843,226.516l-.728-7.36568s18.4917-32.83632,24.10129-35.64112,9.3493-2.80479,9.3493-2.80479,18.6986-19.63353,39.26707-9.3493,69.18484,75.72935,69.18484,75.72935-34.59242,2.80479-29.91777,29.91777c0,0,34.59242,88.81838,25.24312,89.75331s-37.39721-5.60959-66.38,7.47944-42.07186,4.67465-42.07186,4.67465l-14.95889-48.61637s-28.04791-15.89382-27.113-29.91777L772.515,318.60661l3.73972-34.59242,24.77565-28.51537S796.35573,251.75909,827.20843,226.516Z" transform="translate(-62.24207 -126.55043)" fill="#575a89"/>
                            <path d="M766.18031,174.79171a11.73657,11.73657,0,0,1-4.616,3.14369c-1.81051.51062-4.09154-.11555-4.81307-1.85281l1.34411-1.74939-4.02944-2.5039,1.75581-2.06012-3.24556-3.5243,2.56163-2.03236-4.87719-3.48808c6.32111-10.31466,13.15729-20.75845,23.13705-27.59607s23.9246-9.26726,34.14813-2.7998c5.30792,3.35782,9.06128,8.68723,12.06785,14.20172,6.04656,11.09024,11.37634,21.9007,12.16329,34.50764-3.79816-3.69869-9.34319-5.783-13.14136-9.48173a28.463,28.463,0,0,0-5.66925-4.64065c-3.97269-2.24026-8.726-2.43845-13.17348-3.449-3.73952-.84974-6.16609-2.53534-8.99529-4.94505-3.36564-2.8666-3.50275-1.7582-6.90135,1.46926Q775.04391,166.39718,766.18031,174.79171Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                        </svg>
                    </div>
                    <div class="hr-text hr-text-center hr-text-spaceless">Посадочная страница</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Заголовок</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="page[pagetitle]" class="form-control" autocomplete="off" required value="<?= (isset($_POST['page']['pagetitle']) ? $_POST['page']['pagetitle'] : 'Онлайн-оплата') ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Псевдоним</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="page[alias]" class="form-control" autocomplete="off" required value="<?= (isset($_POST['page']['alias']) ? $_POST['page']['alias'] : 'online-oplata') ?>">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Шаблон</label>
                            <select name="page[template]" class="form-select mb-0">
                                <option value="0">Нет</option>
    <?php if (is_array($templatesList) and !empty($templatesList)) : ?>
        <?php foreach ($templatesList as $template) : ?>
                                <option value="<?= $template['id']; ?>"<?= (isset($_POST['page']['template']) && (intval($_POST['page']['template']) === intval($template['id'])) ? ' selected' : '') ?>><?= $template['templatename']; ?></option>
        <?php endforeach; ?>
    <?php endif?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="page[hidemenu]" value="1" />
                <input type="hidden" name="page[published]" value="1" />
                <input type="hidden" name="page[publishedon]" value="<?= time(); ?>" />
                <input type="hidden" name="page[publishedby]" value="1" />
                <input type="hidden" name="page[alias_visible]" value="1" />
                <input type="hidden" name="page[donthit]" value="0" />
                <input type="hidden" name="page[searchable]" value="0" />
                <input type="hidden" name="page[cacheable]" value="0" />
                <input type="hidden" name="prev_stage" value="0" />
                <input type="hidden" name="next_stage" value="2" />
                <div class="row align-items-center mt-3">
                    <div class="col-4">
                        <div class="progress">
                            <div class="progress-bar" style="width: 33%" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" aria-label="33% Complete">
                                <span class="visually-hidden">33% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="btn-list justify-content-end">
                            <input type="submit" name="prev" class="btn btn-link link-secondary" value="Назад" />
                            <input type="submit" name="next" class="btn btn-primary" value="Далее" />
                        </div>
                    </div>
                </div>
            </form>
<?php elseif ($stage === 2) : ?>
            <form method="post" action="">
                <div class="card card-md">
                    <div class="card-body text-center py-4 p-sm-5">
                        <svg id="b59c9f89-c93e-413f-a14b-776784e615e2" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" height="120" viewBox="0 0 1075.51587 646.89913">
                            <title>sign_in</title>
                            <path d="M136.59282,733.55706l63.43134-91.13824L136.56059,743.4707l-.037,10.3692q-6.78516-.05706-13.43322-.44141c-.02019-2.35442,5.37117-131.89917,5.142-134.24281.3129-.0888.48846-12.252.6126-12.78462L62.57779,506.77732,128.91613,596.945l.1978,2.66552,3.93549-100.64793L76.0857,395.895,133.5,481.27779c.02132-1.3695,2.71745-208.02265,2.744-209.29776.01628.67732,1.25371,164.45521,1.26281,165.14538l54.47905-65.92288-54.53131,80.08836-.27852,90.07922,50.556-87.10756C124.63867,581.2954,138.60155,536.8418,136.99011,604.702l73.43568-121.29149L136.92724,622.17566Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <path d="M1106.98992,745.9302l26.4334-37.97954-26.44683,42.1108-.0154,4.3211q-2.82754-.02378-5.598-.18395c-.00841-.98114,2.2383-54.96562,2.14282-55.94228.13039-.037.20355-5.10569.25528-5.32766L1076.146,651.42551l27.64482,37.57509.08242,1.11079,1.64-41.94247-23.73818-42.95081,23.92592,35.58112c.00889-.57071,1.13243-86.68815,1.14349-87.21952.00678.28226.52245,68.53253.52625,68.82014l22.70275-27.47169L1107.349,628.303l-.11607,37.53823,21.06792-36.29986c-26.29252,52.9377-20.47384,34.4128-21.14537,62.69183l30.60245-50.54515-30.62865,57.82684Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <ellipse cx="536" cy="625.89913" rx="536" ry="21" fill="#d0cde1"/>
                            <rect x="184" y="248.03055" width="612" height="364.87" fill="#3f3d56"/>
                            <path d="M397.70209,374.581a146.35424,146.35424,0,0,1-146.32,143.97c-1.72,0-3.43-.03-5.14-.09V374.581Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M582.17908,739.45064H523.9723a31.00847,31.00847,0,0,1,58.20678,0Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M858.24207,550.72094v122.9a61.52336,61.52336,0,0,1,0-122.9Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <rect x="279.45927" y="380.6149" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="279.45927" y="451.67903" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="553.59711" y="522.74315" width="148.00427" height="36.06239" rx="18.03118" fill="#fff"/>
                            <path d="M955.29387,238.67007s16.82875,6.54451,17.76368,28.04791-4.67465,81.33893-4.67465,81.33893,20.56846,29.91777,4.67465,38.33214-17.76368-33.65749-17.76368-33.65749l-18.6986-88.81837S947.81443,237.73514,955.29387,238.67007Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M783.26671,307.85491s-40.202,26.178-42.07187,5.60958S753.34894,212.492,753.34894,212.492s1.86986-34.59242,21.50339-26.178-5.60958,37.39721-5.60958,37.39721l2.80479,65.44511,18.69861-.93493Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M847.77689,332.1631s-123.41079-45.81159-116.86628,7.47944,38.33214,120.606,38.33214,120.606l11.21917,17.76368h23.37325L788.87629,381.7144s53.291,29.91777,69.18484,25.24312c0,0,20.56846,137.43475,28.04791,147.719s20.56846,134.63,20.56846,134.63l12.15409,14.95888h23.37326V493.906s40.202-130.89023,9.3493-137.43475S847.77689,332.1631,847.77689,332.1631Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M777.65712,465.85812s-5.60958-10.28423-14.95888,0-31.78763,22.43833-31.78763,22.43833-48.61637,11.21916-9.3493,20.56847,43.94172,0,43.94172,0,6.54451,1.86986,12.15409,3.73972S819.729,507.93,819.729,502.3204s-12.37851-39.134-17.40842-34.05842S777.65712,465.85812,777.65712,465.85812Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M920.6231,695.01108s-13.7851-11.35244-17.02865-8.10888-20.2722,25.13753-20.2722,25.13753-45.40973,34.86818-4.05444,40.5444,38.92263-9.73066,38.92263-9.73066h12.9742c1.62178,0,23.51576-9.73066,22.70487-13.7851s-10.54155-37.11568-13.7851-34.7756S920.6231,695.01108,920.6231,695.01108Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <circle cx="733.17873" cy="44.80466" r="31.78763" fill="#a0616a"/>
                            <path d="M817.85913,167.61537s24.30818,20.56847,41.13693,20.56847S834.68787,226.516,834.68787,226.516l-36.46228-32.72256Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M827.20843,226.516l-.728-7.36568s18.4917-32.83632,24.10129-35.64112,9.3493-2.80479,9.3493-2.80479,18.6986-19.63353,39.26707-9.3493,69.18484,75.72935,69.18484,75.72935-34.59242,2.80479-29.91777,29.91777c0,0,34.59242,88.81838,25.24312,89.75331s-37.39721-5.60959-66.38,7.47944-42.07186,4.67465-42.07186,4.67465l-14.95889-48.61637s-28.04791-15.89382-27.113-29.91777L772.515,318.60661l3.73972-34.59242,24.77565-28.51537S796.35573,251.75909,827.20843,226.516Z" transform="translate(-62.24207 -126.55043)" fill="#575a89"/>
                            <path d="M766.18031,174.79171a11.73657,11.73657,0,0,1-4.616,3.14369c-1.81051.51062-4.09154-.11555-4.81307-1.85281l1.34411-1.74939-4.02944-2.5039,1.75581-2.06012-3.24556-3.5243,2.56163-2.03236-4.87719-3.48808c6.32111-10.31466,13.15729-20.75845,23.13705-27.59607s23.9246-9.26726,34.14813-2.7998c5.30792,3.35782,9.06128,8.68723,12.06785,14.20172,6.04656,11.09024,11.37634,21.9007,12.16329,34.50764-3.79816-3.69869-9.34319-5.783-13.14136-9.48173a28.463,28.463,0,0,0-5.66925-4.64065c-3.97269-2.24026-8.726-2.43845-13.17348-3.449-3.73952-.84974-6.16609-2.53534-8.99529-4.94505-3.36564-2.8666-3.50275-1.7582-6.90135,1.46926Q775.04391,166.39718,766.18031,174.79171Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                        </svg>
                    </div>
                    <div class="hr-text hr-text-center hr-text-spaceless">Настройка плагина</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Плейсхолдер формы</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="plugin[placeholder]" class="form-control" autocomplete="off" required value="payButton">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Режим работы плагина</label>
                            <select name="plugin[mode]" class="form-select mb-0">
    <?php if (is_array($pluginModes) and !empty($pluginModes)) : ?>
        <?php foreach ($pluginModes as $mode) : ?>
                                <option value="<?= $mode; ?>"><?= $mode; ?></option>
        <?php endforeach; ?>
    <?php endif?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Тестовый API-мерчант</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="plugin[testMerchant]" class="form-control" autocomplete="off" value="">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Тестовый API-токен</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="plugin[testToken]" class="form-control" autocomplete="off" value="">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Боевой API-мерчант</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="plugin[productMerchant]" class="form-control" autocomplete="off" value="">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Боевой API-токен</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="plugin[productToken]" class="form-control" autocomplete="off" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="page[pagetitle]" value="<?= $_POST['page']['pagetitle']; ?>" />
                <input type="hidden" name="page[alias]" value="<?= $_POST['page']['alias']; ?>" />
                <input type="hidden" name="page[template]" value="<?= $_POST['page']['template']; ?>" />
                <input type="hidden" name="page[hidemenu]" value="<?= $_POST['page']['hidemenu']; ?>" />
                <input type="hidden" name="page[published]" value="<?= $_POST['page']['published']; ?>" />
                <input type="hidden" name="page[publishedon]" value="<?= $_POST['page']['publishedon']; ?>" />
                <input type="hidden" name="page[publishedby]" value="<?= $_POST['page']['publishedby']; ?>" />
                <input type="hidden" name="page[alias_visible]" value="<?= $_POST['page']['alias_visible']; ?>" />
                <input type="hidden" name="page[donthit]" value="<?= $_POST['page']['donthit']; ?>" />
                <input type="hidden" name="page[searchable]" value="<?= $_POST['page']['searchable']; ?>" />
                <input type="hidden" name="page[cacheable]" value="<?= $_POST['page']['cacheable']; ?>" />
                <input type="hidden" name="prev_stage" value="1" />
                <input type="hidden" name="next_stage" value="3" />
                <div class="row align-items-center mt-3">
                    <div class="col-4">
                        <div class="progress">
                            <div class="progress-bar" style="width: 66%" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" aria-label="66% Complete">
                                <span class="visually-hidden">66% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="btn-list justify-content-end">
                            <input type="submit" name="prev" class="btn btn-link link-secondary" value="Назад" />
                            <input type="submit" name="next" class="btn btn-primary" value="Далее" />
                        </div>
                    </div>
                </div>
            </form>
<?php elseif ($stage === 3) : ?>
            <form method="post" action="">
                <div class="card card-md">
                    <div class="card-body text-center py-4 p-sm-5">
                        <svg id="b59c9f89-c93e-413f-a14b-776784e615e2" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" height="120" viewBox="0 0 1075.51587 646.89913">
                            <title>sign_in</title>
                            <path d="M136.59282,733.55706l63.43134-91.13824L136.56059,743.4707l-.037,10.3692q-6.78516-.05706-13.43322-.44141c-.02019-2.35442,5.37117-131.89917,5.142-134.24281.3129-.0888.48846-12.252.6126-12.78462L62.57779,506.77732,128.91613,596.945l.1978,2.66552,3.93549-100.64793L76.0857,395.895,133.5,481.27779c.02132-1.3695,2.71745-208.02265,2.744-209.29776.01628.67732,1.25371,164.45521,1.26281,165.14538l54.47905-65.92288-54.53131,80.08836-.27852,90.07922,50.556-87.10756C124.63867,581.2954,138.60155,536.8418,136.99011,604.702l73.43568-121.29149L136.92724,622.17566Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <path d="M1106.98992,745.9302l26.4334-37.97954-26.44683,42.1108-.0154,4.3211q-2.82754-.02378-5.598-.18395c-.00841-.98114,2.2383-54.96562,2.14282-55.94228.13039-.037.20355-5.10569.25528-5.32766L1076.146,651.42551l27.64482,37.57509.08242,1.11079,1.64-41.94247-23.73818-42.95081,23.92592,35.58112c.00889-.57071,1.13243-86.68815,1.14349-87.21952.00678.28226.52245,68.53253.52625,68.82014l22.70275-27.47169L1107.349,628.303l-.11607,37.53823,21.06792-36.29986c-26.29252,52.9377-20.47384,34.4128-21.14537,62.69183l30.60245-50.54515-30.62865,57.82684Z" transform="translate(-62.24207 -126.55043)" fill="#d0cde1"/>
                            <ellipse cx="536" cy="625.89913" rx="536" ry="21" fill="#d0cde1"/>
                            <rect x="184" y="248.03055" width="612" height="364.87" fill="#3f3d56"/>
                            <path d="M397.70209,374.581a146.35424,146.35424,0,0,1-146.32,143.97c-1.72,0-3.43-.03-5.14-.09V374.581Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M582.17908,739.45064H523.9723a31.00847,31.00847,0,0,1,58.20678,0Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <path d="M858.24207,550.72094v122.9a61.52336,61.52336,0,0,1,0-122.9Z" transform="translate(-62.24207 -126.55043)" fill="#206bc4"/>
                            <rect x="279.45927" y="380.6149" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="279.45927" y="451.67903" width="422.14211" height="36.06239" rx="18.03118" fill="#fff"/>
                            <rect x="553.59711" y="522.74315" width="148.00427" height="36.06239" rx="18.03118" fill="#fff"/>
                            <path d="M955.29387,238.67007s16.82875,6.54451,17.76368,28.04791-4.67465,81.33893-4.67465,81.33893,20.56846,29.91777,4.67465,38.33214-17.76368-33.65749-17.76368-33.65749l-18.6986-88.81837S947.81443,237.73514,955.29387,238.67007Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M783.26671,307.85491s-40.202,26.178-42.07187,5.60958S753.34894,212.492,753.34894,212.492s1.86986-34.59242,21.50339-26.178-5.60958,37.39721-5.60958,37.39721l2.80479,65.44511,18.69861-.93493Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M847.77689,332.1631s-123.41079-45.81159-116.86628,7.47944,38.33214,120.606,38.33214,120.606l11.21917,17.76368h23.37325L788.87629,381.7144s53.291,29.91777,69.18484,25.24312c0,0,20.56846,137.43475,28.04791,147.719s20.56846,134.63,20.56846,134.63l12.15409,14.95888h23.37326V493.906s40.202-130.89023,9.3493-137.43475S847.77689,332.1631,847.77689,332.1631Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M777.65712,465.85812s-5.60958-10.28423-14.95888,0-31.78763,22.43833-31.78763,22.43833-48.61637,11.21916-9.3493,20.56847,43.94172,0,43.94172,0,6.54451,1.86986,12.15409,3.73972S819.729,507.93,819.729,502.3204s-12.37851-39.134-17.40842-34.05842S777.65712,465.85812,777.65712,465.85812Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <path d="M920.6231,695.01108s-13.7851-11.35244-17.02865-8.10888-20.2722,25.13753-20.2722,25.13753-45.40973,34.86818-4.05444,40.5444,38.92263-9.73066,38.92263-9.73066h12.9742c1.62178,0,23.51576-9.73066,22.70487-13.7851s-10.54155-37.11568-13.7851-34.7756S920.6231,695.01108,920.6231,695.01108Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                            <circle cx="733.17873" cy="44.80466" r="31.78763" fill="#a0616a"/>
                            <path d="M817.85913,167.61537s24.30818,20.56847,41.13693,20.56847S834.68787,226.516,834.68787,226.516l-36.46228-32.72256Z" transform="translate(-62.24207 -126.55043)" fill="#a0616a"/>
                            <path d="M827.20843,226.516l-.728-7.36568s18.4917-32.83632,24.10129-35.64112,9.3493-2.80479,9.3493-2.80479,18.6986-19.63353,39.26707-9.3493,69.18484,75.72935,69.18484,75.72935-34.59242,2.80479-29.91777,29.91777c0,0,34.59242,88.81838,25.24312,89.75331s-37.39721-5.60959-66.38,7.47944-42.07186,4.67465-42.07186,4.67465l-14.95889-48.61637s-28.04791-15.89382-27.113-29.91777L772.515,318.60661l3.73972-34.59242,24.77565-28.51537S796.35573,251.75909,827.20843,226.516Z" transform="translate(-62.24207 -126.55043)" fill="#575a89"/>
                            <path d="M766.18031,174.79171a11.73657,11.73657,0,0,1-4.616,3.14369c-1.81051.51062-4.09154-.11555-4.81307-1.85281l1.34411-1.74939-4.02944-2.5039,1.75581-2.06012-3.24556-3.5243,2.56163-2.03236-4.87719-3.48808c6.32111-10.31466,13.15729-20.75845,23.13705-27.59607s23.9246-9.26726,34.14813-2.7998c5.30792,3.35782,9.06128,8.68723,12.06785,14.20172,6.04656,11.09024,11.37634,21.9007,12.16329,34.50764-3.79816-3.69869-9.34319-5.783-13.14136-9.48173a28.463,28.463,0,0,0-5.66925-4.64065c-3.97269-2.24026-8.726-2.43845-13.17348-3.449-3.73952-.84974-6.16609-2.53534-8.99529-4.94505-3.36564-2.8666-3.50275-1.7582-6.90135,1.46926Q775.04391,166.39718,766.18031,174.79171Z" transform="translate(-62.24207 -126.55043)" fill="#2f2e41"/>
                        </svg>
                        <h1 class="mt-5">Установка успешно завершена</h1>
                        <p class="text-muted">Для тестирования формы оплаты перейдите на</p>
                        <p class="text-muted"><a class="btn btn-info" href="<?= "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/{$_POST['page']['alias']}/"; ?>" target="_blank">Страницу тестовой оплаты</a></p>
                        <p class="text-muted">В панели администратора можно более тонко настроить платежную страницу и плагин.</p>
                        <p class="text-muted"><strong>Не забудьте удалить мастер установки!</strong></p>
                    </div>
                </div>
                <input type="hidden" name="prev_stage" value="2" />
                <input type="hidden" name="next_stage" value="4" />
                <div class="row align-items-center mt-3">
                    <div class="col-4">
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="100% Complete">
                                <span class="visually-hidden">100% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="btn-list justify-content-end">
                            <input type="submit" name="prev" class="btn btn-link link-secondary" value="Назад" />
                            <input type="submit" name="next" class="btn btn-danger" value="Удалить мастер" />
                        </div>
                    </div>
                </div>
            </form>
<?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta15/dist/js/tabler.min.js"></script>
</body>
</html>