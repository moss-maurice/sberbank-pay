<?php

namespace module\tabs;

use \mmaurice\modx\Search;
use \mmaurice\qurl\Client;
use \module\helpers\MailerHelper;

class TransactionsTab extends \module\classes\Tab
{
    const PAY_PAGE = 171;
    const ROW_LIMIT = 20;

    public $title = 'Транзакции';
    public $description = 'Список транзакций';
    public $orderPosition = 1;

    protected $messages = [
        0 => 'Не оплачено',
        1 => 'Заморозка',
        2 => 'Оплачено полностью',
        3 => 'Отменено',
        4 => 'Возвращено',
        5 => 'Оплата сторонним банком',
        6 => 'Ошибка',
        7 => 'Оплачено полностью',
    ];

    public function __construct()
    {
        $server = $this->serverName() . '/';

        if (!defined('MODX_BASE_URL')) {
            define('MODX_BASE_URL', $server);
        }

        if (!defined('MODX_SITE_URL')) {
            define('MODX_SITE_URL', $server);
        }
    }

    public function actionIndex()
    {
        $page = array_key_exists('page', $_POST) ? intval($_POST['page']) : 1;

        $search = new Search;
        $modx = $search->modx;

        $query = [
            'alias' => 'pt',
            'from' => $modx->getFullTableName('pay_transaction'),
            'join' => [
                "LEFT JOIN " . $modx->getFullTableName('site_content') . " sc ON sc.id = pt.page_id",
                "LEFT JOIN " . $modx->getFullTableName('pay_transaction') . " ptr ON ptr.referal = pt.order_id",
            ],
            'where' => [
                "pt.deleted_at IS NULL",
                "AND pt.done = '0'",
                "AND pt.referal IS NULL",
            ],
            'order' => [
                "pt.id DESC",
            ],
        ];

        $transactionCounts = $search->getItem(array_merge($query, [
            'select' => [
                "COUNT(pt.id) AS total",
            ],
        ]));

        $transactions = $search->getList(array_merge($query, [
            'select' => [
                "pt.*",
                "sc.pagetitle AS pagetitle",
                "ptr.amount AS post_amount",
            ],
            'offset' => ($page - 1) * self::ROW_LIMIT,
            'limit' => self::ROW_LIMIT,
        ]));

        $transactions = array_map(function ($value) {
            $value['status_msg'] = '&mdash';

            if (array_key_exists(intval($value['status']), $this->messages)) {
                $value['status_msg'] = $this->messages[intval($value['status'])];
            }

            return $value;
        }, $transactions);

        return $this->render('index', [
            'transactions' => $transactions,
            'page' => $page,
            'pages' => ceil(intval($transactionCounts['total']) / self::ROW_LIMIT),
        ]);
    }

    public function actionDeposit()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $search = new Search;
            $modx = $search->modx;

            $transaction = $search->getItem([
                'alias' => 'pt',
                'select' => [
                    'pt.order_id',
                    'ptr.order_id AS referal',
                    'pt.total_amount',
                    'pt.amount',
                    'pt.email',
                ],
                'from' => $modx->getFullTableName('pay_transaction'),
                'join' => [
                    "LEFT JOIN " . $modx->getFullTableName('pay_transaction') . " ptr ON ptr.referal = pt.order_id",
                ],
                'where' => [
                    "pt.id = '{$id}'",
                ],
            ]);

            if (is_array($transaction) and !empty($transaction)) {
                $ordersIds = array_filter([
                    $transaction['order_id'],
                    $transaction['referal'],
                ]);

                if (is_array($ordersIds) and !empty($ordersIds)) {
                    foreach ($ordersIds as $orderId) {
                        $response = $this->post('/pay/api/deposit', [
                            'orderNumber' => $orderId,
                        ]);

                        if (intval($response->data->errorCode) === 0) {
                            $modx->db->update([
                                'status' => $response->data->orderStatus,
                            ], $modx->getFullTableName('pay_transaction'), "order_id = '{$orderId}'");
                        }
                    }

                    $body = MailerHelper::renderTemplate('deposite', [
                        'balance' => $transaction['amount'],
                    ]);

                    $result = MailerHelper::send($transaction['email'], 'Разморозка предоплаты', $body);
                }
            }
        }

        return $this->actionIndex();
    }

    public function actionRefund()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $search = new Search;
            $modx = $search->modx;

            $transaction = $search->getItem([
                'alias' => 'pt',
                'select' => [
                    'pt.order_id',
                    'ptr.order_id AS referal',
                ],
                'from' => $modx->getFullTableName('pay_transaction'),
                'join' => [
                    "LEFT JOIN " . $modx->getFullTableName('pay_transaction') . " ptr ON ptr.referal = pt.order_id",
                ],
                'where' => [
                    "pt.id = '{$id}'",
                ],
            ]);

            if (is_array($transaction) and !empty($transaction)) {
                $ordersIds = array_filter([
                    $transaction['order_id'],
                    $transaction['referal'],
                ]);

                if (is_array($ordersIds) and !empty($ordersIds)) {
                    foreach ($ordersIds as $orderId) {
                        $response = $this->post('/pay/api/refund', [
                            'orderNumber' => $orderId,
                        ]);

                        if (intval($response->data->errorCode) === 0) {
                            $modx->db->update([
                                'status' => $response->data->orderStatus,
                            ], $modx->getFullTableName('pay_transaction'), "order_id = '{$orderId}'");
                        }
                    }
                }
            }
        }

        return $this->actionIndex();
    }

    public function actionReverse()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $search = new Search;
            $modx = $search->modx;

            $transaction = $search->getItem([
                'alias' => 'pt',
                'select' => [
                    'pt.order_id',
                    'ptr.order_id AS referal',
                ],
                'from' => $modx->getFullTableName('pay_transaction'),
                'join' => [
                    "LEFT JOIN " . $modx->getFullTableName('pay_transaction') . " ptr ON ptr.referal = pt.order_id",
                ],
                'where' => [
                    "pt.id = '{$id}'",
                ],
            ]);

            if (is_array($transaction) and !empty($transaction)) {
                $ordersIds = array_filter([
                    $transaction['order_id'],
                    $transaction['referal'],
                ]);

                if (is_array($ordersIds) and !empty($ordersIds)) {
                    foreach ($ordersIds as $orderId) {
                        $response = $this->post('/pay/api/reverse', [
                            'orderNumber' => $orderId,
                        ]);

                        if (intval($response->data->errorCode) === 0) {
                            $modx->db->update([
                                'status' => $response->data->orderStatus,
                            ], $modx->getFullTableName('pay_transaction'), "order_id = '{$orderId}'");
                        }
                    }
                }
            }
        }

        return $this->actionIndex();
    }

    public function actionRequest()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $search = new Search;
            $modx = $search->modx;

            $transaction = $search->getItem([
                'select' => '*',
                'from' => $modx->getFullTableName('pay_transaction'),
                'where' => [
                    "id = '{$id}'",
                ],
            ]);

            if (is_array($transaction) and !empty($transaction)) {
                $balance = floatval($transaction['total_amount']) - floatval($transaction['amount']);

                $body = MailerHelper::renderTemplate('request', [
                    'link' => $modx->makeUrl(self::PAY_PAGE) . "?userdata[referal]={$transaction['order_id']}&type=onestage&amount={$balance}",
                    'balance' => $balance,
                ]);

                $result = MailerHelper::send($transaction['email'], 'Оплата остатка суммы', $body);
            }
        }

        return $this->actionIndex();
    }

    public function actionDone()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $modx = (new Search)->modx;

            $modx->db->update([
                'done' => 1,
            ], $modx->getFullTableName('pay_transaction'), "id = '{$id}'");
        }

        return $this->actionIndex();
    }

    public function actionDelete()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;

        if ($id) {
            $modx = (new Search)->modx;

            $modx->db->update([
                'deleted_at' => date('Y-m-d H:i:s'),
            ], $modx->getFullTableName('pay_transaction'), "id = '{$id}'");
        }

        return $this->actionIndex();
    }

    public function actionSaveTotalAmount()
    {
        $id = array_key_exists('id', $_POST) ? intval($_POST['id']) : null;
        $totalAmount = array_key_exists('totalAmount', $_POST) ? intval($_POST['totalAmount']) : null;

        if ($id and $totalAmount) {
            $search = new Search;
            $modx = $search->modx;

            $modx->db->update([
                'total_amount' => $totalAmount,
            ], $modx->getFullTableName('pay_transaction'), "id = '{$id}'");
        }

        return $this->actionIndex();
    }

    protected function post($uri, $fields = [])
    {
        $request = (new Client)->request();

        $server = $this->serverName();

        $response = $request->post("{$server}{$uri}", $fields);

        return $response->getResponseBody();
    }

    protected function serverName()
    {
        return (!isset($_SERVER["HTTPS"]) ? (strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) !== 'https' ? 'http' : 'https') : 'https') . '://' . $_SERVER['HTTP_HOST'];
    }
}
