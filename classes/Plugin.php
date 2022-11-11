<?php

namespace mmaurice\sberpay\classes;

use \mmaurice\sberpay\classes\Logger;
use \mmaurice\sberpay\classes\PluginCore;

class Plugin extends PluginCore
{
    const PLUGIN_NAME = 'sberbank';
    const PLUGIN_VERSION = '0.40.1';
    const PLUGIN_AUTHOR = 'Viktor Voronkov';
    const PLUGIN_AUTHOR_EMAIL = 'kreexus@yandex.ru';
    const PLUGIN_CONTAINER = 'SberPay';

    const MODE_TEST = 'test';
    const MODE_PROD = 'product';

    const TYPE_ONESTAGE = 'onestage';
    const TYPE_TWOSTAGE = 'twostage';

    const COLOR_THEME_DEFAULT = 'default';
    const COLOR_THEME_CUSTOM = 'custom';

    const CHOICE_NO = 'no';
    const CHOICE_YES = 'yes';
    const CHOICE_ON = 'on';
    const CHOICE_OFF = 'off';

    const DEFAULT_PLACEHOLDER = 'payButton';

    const DEFAULT_BUNDLE = 'Оплата товаров и услуг';

    public $productMerchant = 'product-api-merchant';
    public $productToken = 'product-api-token';
    public $testMerchant = 'test-api-merchant';
    public $testToken = 'test-api-token';
    public $payButtonToken = 'pay-button-token';
    public $mode;
    public $type;
    public $restUriTest = 'https://3dsec.sberbank.ru/payment/rest/';
    public $restUriProduction = 'https://securepayments.sberbank.ru/payment/rest/';
    public $shopkeeper;
    public $urlProcessing = '/pay/processing';
    public $urlSuccess = '/pay/success';
    public $urlFail = '/pay/fail';
    public $urlLink = '/pay/link';
    public $urlDeposit = '/pay/api/deposit';
    public $urlReverse = '/pay/api/reverse';
    public $urlRefund = '/pay/api/refund';
    public $urlStatus = '/pay/api/status';
    public $urlProcessingExternal;
    public $urlSuccessExternal;
    public $urlFailExternal;
    public $pageHandler = 1;
    public $queryLog;
    public $queryLogPath = '~/assets/plugins/sberbank/logs';
    public $minAmount = 100;
    public $buttonCaption = 'Оплатить';
    public $currencyCaption = 'руб.';
    public $currencyShow;
    public $placeholder;
    public $autoOrderId;
    public $debugMode;
    public $colorTheme;

    protected $host = '';
    protected $sessionHost;
    protected $sessionName;

    public function __construct($properties = array())
    {
        parent::__construct($properties);

        return $this->configure($properties);
    }

    public function configure($properties = array())
    {
        $this->mode = self::MODE_TEST;
        $this->type = self::TYPE_ONESTAGE;
        $this->shopkeeper = self::CHOICE_NO;
        $this->urlProcessingExternal = self::CHOICE_OFF;
        $this->urlDepositExternal = self::CHOICE_OFF;
        $this->urlSuccessExternal = self::CHOICE_OFF;
        $this->urlFailExternal = self::CHOICE_OFF;
        $this->queryLog = self::CHOICE_OFF;
        $this->currencyShow = self::CHOICE_ON;
        $this->placeholder = self::DEFAULT_PLACEHOLDER;
        $this->autoOrderId = self::CHOICE_ON;
        $this->debugMode = self::CHOICE_OFF;
        $this->colorTheme = self::COLOR_THEME_DEFAULT;

        $this->sessionHost = self::PLUGIN_NAME;

        if (array_key_exists('HTTP_ORIGIN', $_SERVER) and !empty($_SERVER['HTTP_ORIGIN'])) {
            $this->host = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_HOST', $_SERVER) and !empty($_SERVER['HTTP_HOST'])) {
            if (array_key_exists('REQUEST_SCHEME', $_SERVER) and !empty($_SERVER['REQUEST_SCHEME'])) {
                $this->host = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
            }
        }

        // Назначение свойст класса из конфигурации модуля
        if (is_array($properties) and !empty($properties)) {
            foreach ($properties as $name => $value) {
                if (property_exists($this, $name)) {
                    $this->$name = trim($value);
                }
            }
        }

        // Генерируем уникальный sessionName
        $this->sessionName = $this->placeholder;

        // Валидация пути лог-файлов
        $this->valideLogPath();

        return;
    }

    public function runSnippet($params = array())
    {
        $params = array_filter($params);

        $this->configure(array_filter($params));

        $html = $this->makeForm(array_filter(array(
            'action' => (array_key_exists('action', $params) ? $params['action'] : null),
        )));

        echo $html;

        return;
    }

    public function run()
    {
        $modx = $this->injector->modx();

        $url = preg_replace('/^([^\?]*)(\?.*)$/i', '$1', $_SERVER['REQUEST_URI']);

        switch ($modx->event->name) {
            case 'OnPageNotFound':
                $this->setResponseCode(200);

                $this->debug('Хост сессии', "{$this->sessionHost} > {$this->sessionName} ==> " . ($this->checkSession() ? 'true' : 'false'));
                $this->debug('Мерчант', $this->getMerchant());

                if (($url === $this->urlProcessing) and ($this->urlProcessingExternal === 'off')) {
                    $this->makeProcessing();
                } else if (($url === $this->urlSuccess) and ($this->urlSuccessExternal === 'off')) {
                    $this->makeSuccess();
                } else if (($url === $this->urlFail) and ($this->urlFailExternal === 'off')) {
                    $this->makeFail();
                } else if ($url === $this->urlLink) {
                    $this->makeLink();
                } else if ($url === $this->urlReverse) {
                    $this->apiReverse();
                } else if ($url === $this->urlRefund) {
                    $this->apiRefund();
                } else if ($url === $this->urlStatus) {
                    $this->apiStatus();
                }

                if ($this->type === self::TYPE_TWOSTAGE) {
                    if ($url === $this->urlDeposit) {
                        $this->apiDeposit();
                    }
                }
            break;
            case 'OnParseDocument':
                $this->prepareChunks();
            break;
            default:
            break;
        }
    }

    public function makeProcessing()
    {
        $modx = $this->injector->modx();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = (array_key_exists('amount', $_REQUEST) ? $_REQUEST['amount'] : null);
            $orderNumber = ($this->autoOrderId === self::CHOICE_OFF ? (array_key_exists('orderNumber', $_REQUEST) ? $_REQUEST['orderNumber'] : time()) : time());
            $orderBundle = array_key_exists('orderBundle', $_REQUEST) ? $_REQUEST['orderBundle'] : null;
            $phone = (array_key_exists('phone', $_REQUEST) ? $_REQUEST['phone'] : null);
            $email = (array_key_exists('email', $_REQUEST) ? $_REQUEST['email'] : null);
            $pageTitle = (array_key_exists('pageTitle', $_REQUEST) ? $_REQUEST['pageTitle'] : null);
            $pageId = (array_key_exists('pageId', $_REQUEST) ? $_REQUEST['pageId'] : null);
            $type = (array_key_exists('type', $_REQUEST) ? $_REQUEST['type'] : $this->type);
            $userdata = (array_key_exists('userdata', $_REQUEST) ? $_REQUEST['userdata'] : []);
            $params = (array_key_exists('params', $_REQUEST) ? (!is_null($_REQUEST['params']) ? $_REQUEST['params'] : null) : null);

            if ($this->checkHandler()) {
                $fields = array_filter(array(
                    'amount' => floatval($amount),
                    'orderNumber' => $orderNumber,
                    'orderBundle' => $orderBundle,
                    'phone' => $phone,
                    'email' => $email,
                    'pageTitle' => $pageTitle,
                    'pageId' => intval($pageId),
                    'params' => $params,
                    'type' => $type,
                    'userdata' => $userdata,
                ));

                if (realpath(dirname(dirname(__FILE__)) . '/custom/makeProcessing.php')) {
                    include realpath(dirname(dirname(__FILE__)) . '/custom/makeProcessing.php');
                }

                $link = $this->runRequest($fields);

                $this->debug('Ссылка на шлюз', $link);

                return $modx->sendRedirect($link);
            } else {
                return false;
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (array_key_exists('orderId', $_GET)) {
                return $modx->sendRedirect($this->checkResult($_GET['orderId']));
            }
        }

        return $modx->sendRedirect($this->getPreviousPageLink());
    }

    public function apiDeposit()
    {
        $modx = $this->injector->modx();

        header("Content-type: application/json; charset=utf-8");

        if ($this->checkHandler()) {
            $this->setSession('orderId', (array_key_exists('orderNumber', $_POST) ? $_POST['orderNumber'] : null));

            $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                'userName' => $this->getMerchant(),
                'password' => $this->getToken(),
                'orderId' => $this->getSession('orderId'),
            )));

            if (intval($response->errorCode) === 0) {
                if ((intval($response->orderStatus) === 1) and ($this->type === self::TYPE_TWOSTAGE)) {
                    $response = $this->request('deposit.do', array_filter(array(
                        'userName' => $this->getMerchant(),
                        'password' => $this->getToken(),
                        'orderId' => $this->getSession('orderId'),
                        'amount' => intval($response->amount),
                    )));

                    if (intval($response->errorCode) === 0) {
                        $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                            'userName' => $this->getMerchant(),
                            'password' => $this->getToken(),
                            'orderId' => $this->getSession('orderId'),
                        )));

                        if (intval($response->errorCode) === 0) {
                            $this->setSession('status', $response);

                            die(json_encode(array(
                                'status' => 'success',
                                'message' => 'Deposition success',
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        } else {
                            die(json_encode(array(
                                'status' => 'fail',
                                'message' => $response->errorMessage,
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        die(json_encode(array(
                            'status' => 'fail',
                            'message' => $response->errorMessage,
                            'code' => $response->errorCode,
                            'data' => $response,
                        ), JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    die(json_encode(array(
                        'status' => 'fail',
                        'message' => 'Incorrect order status',
                        'code' => 0,
                        'data' => [],
                    ), JSON_UNESCAPED_UNICODE));
                }
            } else {
                die(json_encode(array(
                    'status' => 'fail',
                    'message' => $response->errorMessage,
                    'code' => $response->errorCode,
                    'data' => $response,
                ), JSON_UNESCAPED_UNICODE));
            }
        }

        return false;
    }

    public function apiReverse()
    {
        $modx = $this->injector->modx();

        header("Content-type: application/json; charset=utf-8");

        if ($this->checkHandler()) {
            $this->setSession('orderId', (array_key_exists('orderNumber', $_POST) ? $_POST['orderNumber'] : null));

            $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                'userName' => $this->getMerchant(),
                'password' => $this->getToken(),
                'orderId' => $this->getSession('orderId'),
            )));

            if (intval($response->errorCode) === 0) {
                if (((intval($response->orderStatus) === 2) AND ($this->type === self::TYPE_ONESTAGE)) OR ((intval($response->orderStatus) === 1) AND ($this->type === self::TYPE_TWOSTAGE))) {
                    $response = $this->request('reverse.do', array_filter(array(
                        'userName' => $this->getMerchant(),
                        'password' => $this->getToken(),
                        'orderId' => $this->getSession('orderId'),
                        'amount' => intval($response->amount),
                    )));

                    if (intval($response->errorCode) === 0) {
                        $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                            'userName' => $this->getMerchant(),
                            'password' => $this->getToken(),
                            'orderId' => $this->getSession('orderId'),
                        )));

                        if (intval($response->errorCode) === 0) {
                            $this->setSession('status', $response);

                            die(json_encode(array(
                                'status' => 'success',
                                'message' => 'Reverse success',
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        } else {
                            die(json_encode(array(
                                'status' => 'fail',
                                'message' => $response->errorMessage,
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        die(json_encode(array(
                            'status' => 'fail',
                            'message' => $response->errorMessage,
                            'code' => $response->errorCode,
                            'data' => $response,
                        ), JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    die(json_encode(array(
                        'status' => 'fail',
                        'message' => 'Incorrect order status',
                        'code' => 0,
                        'data' => [],
                    ), JSON_UNESCAPED_UNICODE));
                }
            } else {
                die(json_encode(array(
                    'status' => 'fail',
                    'message' => $response->errorMessage,
                    'code' => $response->errorCode,
                    'data' => $response,
                ), JSON_UNESCAPED_UNICODE));
            }
        }

        return false;
    }

    public function apiRefund()
    {
        $modx = $this->injector->modx();

        header("Content-type: application/json; charset=utf-8");

        if ($this->checkHandler()) {
            $this->setSession('orderId', (array_key_exists('orderNumber', $_POST) ? $_POST['orderNumber'] : null));

            $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                'userName' => $this->getMerchant(),
                'password' => $this->getToken(),
                'orderId' => $this->getSession('orderId'),
            )));

            if (intval($response->errorCode) === 0) {
                if (intval($response->orderStatus) === 2) {
                    $response = $this->request('refund.do', array_filter(array(
                        'userName' => $this->getMerchant(),
                        'password' => $this->getToken(),
                        'orderId' => $this->getSession('orderId'),
                        'amount' => intval($response->amount),
                    )));

                    if (intval($response->errorCode) === 0) {
                        $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                            'userName' => $this->getMerchant(),
                            'password' => $this->getToken(),
                            'orderId' => $this->getSession('orderId'),
                        )));

                        if (intval($response->errorCode) === 0) {
                            $this->setSession('status', $response);

                            die(json_encode(array(
                                'status' => 'success',
                                'message' => 'Refund success',
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        } else {
                            die(json_encode(array(
                                'status' => 'fail',
                                'message' => $response->errorMessage,
                                'code' => $response->errorCode,
                                'data' => $response,
                            ), JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        die(json_encode(array(
                            'status' => 'fail',
                            'message' => $response->errorMessage,
                            'code' => $response->errorCode,
                            'data' => $response,
                        ), JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    die(json_encode(array(
                        'status' => 'fail',
                        'message' => 'Incorrect order status',
                        'code' => 0,
                        'data' => [],
                    ), JSON_UNESCAPED_UNICODE));
                }
            } else {
                die(json_encode(array(
                    'status' => 'fail',
                    'message' => $response->errorMessage,
                    'code' => $response->errorCode,
                    'data' => $response,
                ), JSON_UNESCAPED_UNICODE));
            }
        }

        return false;
    }

    public function apiStatus()
    {
        $modx = $this->injector->modx();

        header("Content-type: application/json; charset=utf-8");

        if ($this->checkHandler()) {
            $this->setSession('orderId', (array_key_exists('orderNumber', $_POST) ? $_POST['orderNumber'] : null));

            $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                'userName' => $this->getMerchant(),
                'password' => $this->getToken(),
                'orderId' => $this->getSession('orderId'),
            )));

            die(json_encode(array(
                'status' => 'success',
                'message' => 'Status success',
                'code' => $response->errorCode,
                'data' => $response,
            ), JSON_UNESCAPED_UNICODE));
        }

        return false;
    }

    protected function runRequest($properties = array())
    {
        $modx = $this->injector->modx();

        $this->setSession('returnUrl', $this->getPreviousPageLink());

        $properties = array_filter($properties);

        $amount = (array_key_exists('amount', $properties) ? $properties['amount'] : null);
        $orderNumber = (array_key_exists('orderNumber', $properties) ? intval($properties['orderNumber']) : time());
        $orderBundle = (array_key_exists('orderBundle', $properties) ? $properties['orderBundle'] : null);
        $phone = (array_key_exists('phone', $properties) ? $properties['phone'] : null);
        $email = (array_key_exists('email', $properties) ? $properties['email'] : null);
        $pageTitle = (array_key_exists('pageTitle', $properties) ? $properties['pageTitle'] : null);
        $pageId = (array_key_exists('pageId', $properties) ? $properties['pageId'] : null);
        $jsonParams = (array_key_exists('params', $properties) ? json_encode($properties['params']) : null);
        $type = (array_key_exists('type', $properties) ? $properties['type'] : $this->type);
        $userdata = (array_key_exists('userdata', $properties) ? $properties['userdata'] : []);

        $this->setSession('orderBundle', $orderBundle);
        $this->setSession('orderNumber', $orderNumber);
        $this->setSession('pageTitle', $pageTitle);
        $this->setSession('pageId', $pageId);

        $params = array(
            'userName' => $this->getMerchant(),
            'password' => $this->getToken(),
            'description' => 'Заказ №' . $orderNumber . ' на ' . $_SERVER['SERVER_NAME'],
            'orderNumber' => urlencode($orderNumber),
            'amount' => $this->prepareAmount(floatval(urlencode($amount))),
            'phone' => (!is_null($phone) ? $phone : ''),
            'email' => (!is_null($email) ? $email : ''),
            'returnUrl' => $this->host . $this->urlProcessing,
            'failUrl' => $this->host . $this->urlFail,
            'jsonParams' => (!is_null($jsonParams) ? $jsonParams : null),
        );

        $params = array_filter($params);

        if (!is_null($pageTitle) and !is_null($pageId)) {
            $params = array_merge($params, array(
                'orderBundle' => json_encode(array(
                    'cartItems' => array(
                        'items' => array(
                            array(
                                'positionId' => 1,
                                'name' => (!is_null($pageTitle) ? $pageTitle : ''),
                                'quantity' => array(
                                    'value' => 1,
                                    'measure' => 'шт'
                                ),
                                'itemAmount' => $this->prepareAmount(floatval(urlencode($amount))),
                                'itemCode' => (!is_null($pageId) ? $pageId : ''),
                                'tax' => array(
                                    'taxType' => 0,
                                    'taxSum' => 0
                                ),
                                'itemPrice' => $this->prepareAmount(floatval(urlencode($amount))),
                            ),
                        ),
                    ),
                ), JSON_UNESCAPED_UNICODE),
            ));
        }

        $this->debug('Mode', $this->mode);
        $this->debug('Type', $type);
        $this->debug('Userdata', $userdata);

        if ($type === self::TYPE_TWOSTAGE) {
            $response = $this->request('registerPreAuth.do', $params);
        } else {
            $response = $this->request('register.do', $params);
        }

        if (!is_null($response)) {
            $this->setSession('orderId', $response->orderId);
            $this->setSession('response', $response);
            $this->setSession('type', $type);

            if (is_array($userdata) and !empty($userdata)) {
                foreach ($userdata as $userdataKey => $userdataItem) {
                    $this->setSessionUserdata($userdataKey, $userdataItem);
                }
            }

            if (property_exists($response, 'errorCode')) {
                if ($this->shopkeeper === self::CHOICE_YES) {
                    $this->dbUpdate(array(
                        'status' => 2,
                    ), 'manager_shopkeeper', "`id` = '" . urlencode($orderNumber) . "'");
                }

                $this->setSession('error', true);
                $this->setSession('errorCode', intval($response->errorCode));
                $this->setSession('errorMessage', $response->errorMessage);

                return $this->urlFail;
            }

            if (property_exists($response, 'formUrl')) {
                if ($this->shopkeeper === self::CHOICE_YES) {
                    $this->dbUpdate(array(
                        'status' => 2,
                        'payment' => $response->orderId,
                    ), 'manager_shopkeeper', "`id` = '" . urlencode($orderNumber) . "'");
                }

                $this->setSession('error', false);
                $this->setSession('errorCode', 0);
                $this->setSession('errorMessage', '');

                return $response->formUrl;
            }

            if ($this->shopkeeper === self::CHOICE_YES) {
                $this->dbUpdate(array(
                    'status' => 2,
                ), 'manager_shopkeeper', "`id` = '" . urlencode($orderNumber) . "'");
            }
        }

        return $this->urlFail;
    }

    protected function checkResult($orderId)
    {
        $modx = $this->injector->modx();

        if ($this->checkSession()) {
            $fields = array(
                'userName' => $this->getMerchant(),
                'password' => $this->getToken(),
                'orderId' => urlencode($orderId),
            );

            $response = $this->request('getOrderStatus.do', $fields);

            $this->setSession('response', $response);

            if (property_exists($response, 'ErrorCode') and (intval($response->ErrorCode) === 0)) {
                if ($this->shopkeeper === self::CHOICE_YES) {
                    $this->dbUpdate(array(
                        'status' => 6,
                    ), 'manager_shopkeeper', "`payment` = '" . urlencode($orderId) . "'");
                }

                $this->setSession('error', false);
                $this->setSession('errorCode', 0);
                $this->setSession('errorMessage', '');

                return $this->urlSuccess;
            }

            if ($this->shopkeeper === self::CHOICE_YES) {
                $this->dbUpdate(array(
                    'status' => 2,
                ), 'manager_shopkeeper', "`payment` = '" . urlencode($orderId) . "'");
            }

            $this->setSession('error', true);
            $this->setSession('errorCode', intval($response->errorCode));
            $this->setSession('errorMessage', $response->errorMessage);

            return $this->urlFail;
        }

        return false;
    }

    public function makeSuccess()
    {
        $modx = $this->injector->modx();

        if ($this->checkSession()) {
            if (intval($response->errorCode) === 0) {
                $response = $this->request('getOrderStatusExtended.do', array_filter(array(
                    'userName' => $this->getMerchant(),
                    'password' => $this->getToken(),
                    'orderId' => $this->getSession('orderId'),
                )));

                if (intval($response->errorCode) === 0) {
                    $this->setSession('status', $response);
                    $this->setSession('orderNumber', $response->orderNumber);
                    $this->setSession('amount', $response->amount / 100);
                }
            }

            $response = $this->getSession('response');
            $returnUrl = $this->getSession('returnUrl');

            $this->debug('Ответ сервера', $response);
            $this->debug('Ссылка возврата', $returnUrl);
            $this->debug('_SESSION', $_SESSION);

            $session = $_SESSION[$this->sessionHost];

            if (realpath(dirname(dirname(__FILE__)) . '/custom/makeSuccess.php')) {
                include realpath(dirname(dirname(__FILE__)) . '/custom/makeSuccess.php');
            }

            $orderNumber = $this->getSession('orderNumber');
            $amount = $this->getSession('amount');
            $status = $this->getSession('status');
            $referal = $this->getSessionUserdata('referal');

            //$this->dropSession();

            return $this->sendForward($this->pageHandler, $this->makeTemplate('messages/success', array(
                'orderNumber' => $orderNumber,
                'amount' => $amount,
                'response' => $response,
                'status' => $status,
                'referal' => $referal,
                'returnUrl' => $returnUrl,
                'session' => $session,
                'colorTheme' => $this->colorTheme,
            )));
        }

        if ($this->isEmptySession()) {
            return $modx->sendRedirect('/');
        }
    }

    public function makeFail()
    {
        $modx = $this->injector->modx();

        if ($this->checkSession()) {
            $response = $this->getSession('response');
            $returnUrl = $this->getSession('returnUrl');
            $errorCode = $this->getSession('errorCode');
            $errorMessage = $this->getSession('errorMessage');

            $this->debug('Ответ сервера', $response);
            $this->debug('Ссылка возврата', $returnUrl);
            $this->debug('Код ошибки', $errorCode);
            $this->debug('Сообщение ошибки', $errorMessage);
            $this->debug('_SESSION', $_SESSION);

            $session = $_SESSION[$this->sessionHost];

            $logger = new Logger(($this->queryLog === self::CHOICE_OFF) ? false : true);
            $logger->writeLog(var_export($response, true), 'order_fail_' . $response->OrderNumber);

            if (intval($errorCode) > 0) {
                return $this->sendForward($this->pageHandler, $this->makeTemplate('messages/fail', array(
                    'response' => $response,
                    'returnUrl' => $returnUrl,
                    'errorCode' => $errorCode,
                    'errorMessage' => $errorMessage,
                    'session' => $session,
                    'colorTheme' => $this->colorTheme,
                )));
            }

            if (realpath(dirname(dirname(__FILE__)) . '/custom/makeFail.php')) {
                include realpath(dirname(dirname(__FILE__)) . '/custom/makeFail.php');
            }

            $this->dropSession();

            return $this->sendForward($this->pageHandler, $this->makeTemplate('messages/error', array(
                'response' => $response,
                'returnUrl' => $returnUrl,
                'errorCode' => $errorCode,
                'errorMessage' => $errorMessage,
                'session' => $session,
                'colorTheme' => $this->colorTheme,
            )));
        }

        if ($this->isEmptySession()) {
            return $modx->sendRedirect('/');
        }
    }

    public function makeLink()
    {
        $modx = $this->injector->modx();

        $orderNumber = ($this->autoOrderId === self::CHOICE_OFF ? (array_key_exists('orderNumber', $_GET) ? $_GET['orderNumber'] : time()) : time());
        $orderBundle = (array_key_exists('orderBundle', $_GET) ? $_GET['orderBundle'] : null);
        $amount = (array_key_exists('amount', $_GET) ? $_GET['amount'] : null);
        $phone = (array_key_exists('phone', $_GET) ? $_GET['phone'] : null);
        $email = (array_key_exists('email', $_GET) ? $_GET['email'] : null);
        $pageTitle = (array_key_exists('pageTitle', $_GET) ? $_GET['pageTitle'] : null);
        $pageId = (array_key_exists('pageId', $_GET) ? $_GET['pageId'] : null);
        $handler = (array_key_exists('handler', $_GET) ? $_GET['handler'] : self::DEFAULT_PLACEHOLDER);

        if (($handler === $this->placeholder) or is_null($handler) or empty($handler)) {
            $this->setAlias($handler);

            $fields = array(
                'amount' => $amount,
                'orderNumber' => $orderNumber,
                'orderBundle' => $orderBundle,
                'phone' => $phone,
                'email' => $email,
                'pageTitle' => $pageTitle,
                'pageId' => $pageId,
            );

            $link = $this->runRequest($fields);

            $this->debug('Ссылка на шлюз', $link);

            return $modx->sendRedirect($link);
        }

        return false;
    }

    public function prepareChunks()
    {
        $modx = $this->injector->modx();

        $this->dropSession();

        // Контейнер под новый контент
        $replacement = '';

        // Получаем идентификатор текущей страницы
        $id = $modx->documentIdentifier;

        // Получаем поля текущего документа
        $document = [];

        if ($id and is_array($modx->getDocument($id))) {
            $document = array_map(function($value) {
                return (!is_numeric($value) ? (!is_string($value) ? $value : htmlspecialchars($value)) : intval($value));
            }, $modx->getDocument($id));
        }

        // Получаем текущий контент страницы
        $content = &$modx->documentOutput;

        // Параметры для рендеринга шаблона
        $buttonParams = array(
            'id' => $id,
            'document' => $document,
        );

        // Ловим плейсхолдер кнопки

        if (preg_match('/(?:\{\{)' . trim($this->placeholder) . '(?:\}\}|\s*\?\s*([^\}]+)\}\})/i', $content, $matches)) {
            $chunkParams = $this->parseChunkTagParams(htmlentities($matches[0]));
            $params = array_merge($buttonParams, $chunkParams);
            $replacement = $this->makeForm($params);

            // Подменяем на отрендеренный шаблон
            $content = str_replace($matches[0], $replacement, $content);
        }

        return true;
    }

    protected function makeForm($params = array())
    {
        $modx = $this->injector->modx();

        $params = array_merge(array(
            'action' => (array_key_exists('action', $_REQUEST) ? $_REQUEST['action'] : $this->urlProcessing),
            'placeholder' => (array_key_exists('placeholder', $_REQUEST) ? $_REQUEST['placeholder'] : $this->placeholder),
            'orderNumber' => (array_key_exists('orderNumber', $_REQUEST) ? $_REQUEST['orderNumber'] : time()),
            'orderBundle' => (array_key_exists('orderBundle', $_REQUEST) ? $_REQUEST['orderBundle'] : self::DEFAULT_BUNDLE),
            'type' => (array_key_exists('type', $_REQUEST) ? $_REQUEST['type'] : $this->type),
            'userdata' => (array_key_exists('userdata', $_REQUEST) ? $_REQUEST['userdata'] : []),
            'amount' => str_replace(',', '.', (array_key_exists('amount', $_REQUEST) ? $_REQUEST['amount'] : $this->minAmount)),
            'email' => (array_key_exists('email', $_REQUEST) ? $_REQUEST['email'] : ''),
            'phone' => (array_key_exists('phone', $_REQUEST) ? $_REQUEST['phone'] : ''),
            'buttonCaption' => (array_key_exists('buttonCaption', $_REQUEST) ? $_REQUEST['buttonCaption'] : $this->buttonCaption),
            'currencyCaption' => (array_key_exists('currencyCaption', $_REQUEST) ? $_REQUEST['currencyCaption'] : $this->currencyCaption),
            'currencyShow' => ($this->currencyShow === self::CHOICE_ON ? true : false),
            'token' => $this->getToken(),
            'payButtonToken' => $this->payButtonToken,
            'restUri' => $this->getRestUri(),
            'domainRestUri' => rtrim(preg_replace('/(http[s]*\:\/\/[^\/]+\/)([^$]*)/i', '$1', $this->getRestUri()), '/'),
            // Подстановка цены через JS из специального поля.
            // Пока включено всегда, но по идее надо вынести в конфиг
            'autoAmount' => true,
            'handler' => $this->placeholder,
            'id' => $modx->documentIdentifier,
            'document' => $modx->getDocument($modx->documentIdentifier),
        ), $params);

        if ($params['amount'] == 0) {
            $params['amount'] = null;
        }

        $params['colorTheme'] = $this->colorTheme;

        $tmpl = (array_key_exists('tmpl', $params) ? $params['tmpl'] : 'form');

        return $this->makeTemplate($tmpl, $params);
    }

    protected function valideLogPath()
    {
        // Конвертируем путь
        $this->queryLogPath = str_replace('~', $_SERVER['DOCUMENT_ROOT'], $this->queryLogPath);

        // Проверка на существование пути
        if (!realpath($this->queryLogPath) or !file_exists($this->queryLogPath)) {
            // Создаём, если не существует
            mkdir($this->queryLogPath, 0777, true);
        }

        // Финальная валидация пути
        $this->queryLogPath = realpath($this->queryLogPath);

        if ($this->queryLogPath) {
            return true;
        }

        return false;
    }

    protected function getRestUri()
    {
        switch ($this->mode) {
            case self::MODE_PROD:
                return $this->restUriProduction;
            break;
            case self::MODE_TEST:
            default:
                return $this->restUriTest;
            break;
        }
    }

    protected function getMerchant()
    {
        switch ($this->mode) {
            case self::MODE_PROD:
                return $this->productMerchant;
            break;
            case self::MODE_TEST:
            default:
                return $this->testMerchant;
            break;
        }
    }

    protected function getToken()
    {
        switch ($this->mode) {
            case self::MODE_PROD:
                return $this->productToken;
            break;
            case self::MODE_TEST:
            default:
                return $this->testToken;
            break;
        }
    }

    protected function isDebugModeOn()
    {
        return ($this->debugMode === self::CHOICE_ON) ? true : false;
    }

    protected function debug($key, $value)
    {
        if ($this->isDebugModeOn) {
            $value = json_encode($value);

            echo "<script>console.group('{$key}:');console.log({$value});console.groupEnd();</script>";

            return true;
        }

        return false;
    }

    protected function getHandler()
    {
        $handler = array_key_exists('handler', $_REQUEST) ? $_REQUEST['handler'] : self::DEFAULT_PLACEHOLDER;

        $this->debug('Текущий мерчант', $handler);

        return $handler;
    }

    protected function checkHandler()
    {
        $result = ($this->getHandler() === $this->placeholder) ? true : false;

        $this->debug('Выбранный мерчант', $this->placeholder);

        return $result;
    }

    protected function request($method, $data)
    {
        $this->debug('_SESSION', $_SESSION);
        $this->debug('_REQUEST', $_REQUEST);
        $this->debug('Запрос', $method);
        $this->debug('Поля запроса', $data);

        $response = parent::request($method, $data);

        $this->debug('Поля ответа', $response);

        return $response;
    }
}
