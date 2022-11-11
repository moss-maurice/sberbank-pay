<?php

namespace mmaurice\modx;

class Core
{
    const DEFAULT_HOST = 'http://localhost/';

    public $modx;

    protected $options;

    public function __construct($options = [], $debug = false)
    {
        $this->options = $options;
        $this->modx = $this->init($debug);

        return;
    }

    public function getId()
    {
        return $this->modx->documentIdentifier;
    }

    public function init($debug = false)
    {
        if (php_sapi_name() == 'cli') {
            $_SERVER['DOCUMENT_ROOT'] = (array_key_exists('docRoot', $this->options) ? $this->options['docRoot'] : '');
            $_SERVER['REMOTE_ADDR'] = (array_key_exists('host', $this->options) ? $this->options['host'] : self::DEFAULT_HOST);
        }

        if ($debug) {
            $_SESSION['mgrRole'] = 1;
            $_SESSION['mgrValidated'] = true;
        }

        if (!defined('MODX_API_MODE')) {
            define('MODX_API_MODE', true);
        }

        if (!defined('MODX_BASE_PATH')) {
            define('MODX_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
        }

        if (!defined('MODX_BASE_URL')) {
            define('MODX_BASE_URL', (array_key_exists('host', $this->options) ? $this->options['host'] : self::DEFAULT_HOST));
        }

        if (!defined('MODX_SITE_URL')) {
            define('MODX_SITE_URL', (array_key_exists('host', $this->options) ? $this->options['host'] : self::DEFAULT_HOST));
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
}
