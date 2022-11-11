<?php

namespace mmaurice\sberpay\classes;

use \mmaurice\modx\Core;
use \mmaurice\qurl\Client;

class PluginCore
{
    const PLUGIN_CONTAINER = '';

    protected $request;
    protected $injector;

    protected $headerCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    );

    public function __construct($properties = array())
    {
        $this->injector = new Core;
    }

    protected function dbUpdate($fields, $table, $where = '')
    {
        $modx = $this->injector->modx();

        $table = $modx->getFullTableName($table);
        $result = $modx->db->update($fields, $table, $where);

        if ($db->update($fields, $table, $where)) {
            return true;
        }

        return false;
    }

    protected function getTemplateFullPath($tplName)
    {
        $path = realpath(dirname(__FILE__) . '/../templates/custom/' . $tplName . '.php');

        if (!$path) {
            $path = realpath(dirname(__FILE__) . '/../templates/' . $tplName . '.php');
        }

        return $path;
    }

    protected function addTplSystemFields(array &$fields = [])
    {
        $fields['pluginWebRootPath'] = str_replace(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), '', realpath(dirname(__FILE__) . '/..'));
        $fields['pluginContainer'] = lcfirst(static::PLUGIN_CONTAINER);
    }

    protected function makeTemplate($__tplName__, $__variables__ = array())
    {
        $__tplName__ = trim($__tplName__);
        $__tplPath__ = $this->getTemplateFullPath($__tplName__);

        if (!file_exists($__tplPath__) or !is_file($__tplPath__)) {
            die("Template file \"{$__tplName__}\" is not found!");
        }

        $this->addTplSystemFields($__variables__);

        extract($__variables__, EXTR_PREFIX_SAME, 'data');

        ob_start();

        ob_implicit_flush(false);
        include($__tplPath__);

        $content = ob_get_clean();

        return $content;
    }

    protected function render($tplName, $variables = array(), $die = true)
    {
        echo $this->makeTemplate($tplName, $variables);

        if ($die) {
            die();
        }
    }

    protected function getPreviousPageLink()
    {
        if (array_key_exists('HTTP_REFERER', $_SERVER) and !empty($_SERVER['HTTP_REFERER'])) {
            if (!in_array($_SERVER['HTTP_REFERER'], array($this->urlProcessing, $this->urlFail, $this->urlSuccess))) {
                return $_SERVER['HTTP_REFERER'];
            }
        }

        return '/';
    }

    protected function parseChunkTagParams($chunkTag)
    {
        $chunkParams = array();

        preg_match_all('/\&[.]*([^\=\s]+)\=\`([^\`]*)\`/i', $chunkTag, $matches);

        if (is_array($matches) and !empty($matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $chunkParams[str_replace('amp;', '', $matches[1][$i])] = $matches[2][$i];
            }
        }

        return $chunkParams;
    }

    protected function request($method, $data)
    {
        $request = (new Client)->request();

        $request->setBodyUrlEncode();

        $response = $request->post($this->getRestUri() . $method, $data);

        if ($this->queryLog) {
            $logger = new Logger;

            $logContent = '===[REQUEST URL]===' . PHP_EOL
                . $response->getRequestUrl() . PHP_EOL . PHP_EOL
                . '===[REQUEST HEADER]===' . PHP_EOL
                . $response->getRawRequestHeader() . PHP_EOL . PHP_EOL
                . '===[REQUEST BODY]===' . PHP_EOL
                . $response->getRawRequestBody() . PHP_EOL . PHP_EOL
                . '===[RESPONSE HEADER]===' . PHP_EOL
                . $response->getRawResponseHeader() . PHP_EOL . PHP_EOL
                . '===[RESPONSE BODY]===' . PHP_EOL
                . $response->getRawResponseBody() . PHP_EOL . PHP_EOL;

            $logger->writeLog($logContent, 'query');
        }

        if (is_string($response->getResponseBody()) and !empty($response->getResponseBody())) {
            return json_decode($response->getResponseBody());
        }

        return $response->getResponseBody();
    }

    protected function setResponseCode($code)
    {
        if (array_key_exists((integer) $code, $this->headerCodes)) {
            $text = $this->headerCodes[(integer) $code];
        } else {
            exit('Unknown http status code "' . htmlentities((integer) $code) . '".');
        }

        $protocol = 'HTTP/1.1';

        if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
            $protocol = $_SERVER['SERVER_PROTOCOL'];
        }

        header($protocol . ' ' . (integer) $code . ' ' . $text);

        return (integer) $code;
    }

    protected function prepareAmount($amount)
    {
        return intval(floatval($amount) * 100);
    }

    protected function sendForward($id, $content = '')
    {
        $modx = $this->injector->modx();

        $modx->forwards = $modx->forwards - 1;
        $modx->documentIdentifier = $id;
        $modx->documentMethod = 'id';
        $modx->documentObject = $modx->getDocumentObject($modx->documentMethod, $modx->documentIdentifier, 'prepareResponse');
        $modx->documentObject['content'] = $content;
        $modx->documentName = $modx->documentObject['pagetitle'];

        if (!$modx->documentObject['template']) {
            $modx->documentContent = "[*content*]";
        } else {
            $result = $modx->db->select('content', $modx->getFullTableName("site_templates"), "id = '{$modx->documentObject['template']}'");

            if ($template_content = $modx->db->getValue($result)) {
                $modx->documentContent = $template_content;
            } else {
                $modx->messageQuit("Incorrect number of templates returned from database", $sql);
            }
        }

        $modx->minParserPasses = empty($modx->minParserPasses) ? 2 : $modx->minParserPasses;
        $modx->maxParserPasses = empty($modx->maxParserPasses) ? 10 : $modx->maxParserPasses;

        $passes = $modx->minParserPasses;

        for ($i = 0; $i < $passes; $i++) {
            if ($i == ($passes -1)) {
                $st = strlen($modx->documentContent);
            }

            if ($modx->dumpSnippets == 1) {
                $modx->snippetsCode .= "<fieldset><legend><b style ='color: #821517;'>PARSE PASS " . ($i +1) . "</b></legend><p>The following snippets (if any) were parsed during this pass.</p>";
            }

            $modx->documentOutput = $modx->documentContent;
            $modx->invokeEvent("OnParseDocument");
            $modx->documentContent = $modx->documentOutput;
            $modx->documentContent = $modx->mergeSettingsContent($modx->documentContent);
            $modx->documentContent = $modx->mergeDocumentContent($modx->documentContent);
            $modx->documentContent = $modx->mergeSettingsContent($modx->documentContent);
            $modx->documentContent = $modx->mergeChunkContent($modx->documentContent);

            if(isset($modx->config['show_meta']) && $modx->config['show_meta'] ==1) {
                $modx->documentContent = $modx->mergeDocumentMETATags($modx->documentContent);
            }

            $modx->documentContent = $modx->evalSnippets($modx->documentContent);
            $modx->documentContent = $modx->mergePlaceholderContent($modx->documentContent);
            $modx->documentContent = $modx->mergeSettingsContent($modx->documentContent);

            if ($modx->dumpSnippets == 1) {
                $modx->snippetsCode .= "</fieldset><br />";
            }

            if ($i == ($passes -1) && $i < ($modx->maxParserPasses - 1)) {
                $et = strlen($modx->documentContent);

                if ($st != $et) {
                    $passes++;
                }
            }
        }

        $modx->outputContent();

        die();
    }

    public function checkSession()
    {
        if (array_key_exists($this->sessionHost, $_SESSION)) {
            if (array_key_exists($this->sessionName, $_SESSION[$this->sessionHost])) {
                return true;
            }
        }

        return false;
    }

    public function dropSession()
    {
        $_SESSION[$this->sessionHost] = [];
    }

    public function isEmptySession()
    {
        return empty($_SESSION[$this->sessionHost]);
    }

    public function setSession($key, $value = null)
    {
        if (!array_key_exists($this->sessionHost, $_SESSION)) {
            $_SESSION[$this->sessionHost] = [];
        }

        if (!array_key_exists($this->sessionName, $_SESSION[$this->sessionHost])) {
            $_SESSION[$this->sessionHost][$this->sessionName] = [];
        }

        $_SESSION[$this->sessionHost][$this->sessionName][$key] = $value;

        if (is_null($value)) {
            $_SESSION[$this->sessionHost][$this->sessionName][$key] = null;

            unset($_SESSION[$this->sessionHost][$this->sessionName][$key]);
        }
    }

    public function setSessionUserdata($key, $value = null)
    {
        return $this->setSession('userdata', array_merge($this->getSession('userdata', []), [
            $key => $value,
        ]));
    }

    public function getSession($key, $defaultValue = null)
    {
        if (array_key_exists($this->sessionHost, $_SESSION) and array_key_exists($this->sessionName, $_SESSION[$this->sessionHost]) and array_key_exists($key, $_SESSION[$this->sessionHost][$this->sessionName])) {
            return $_SESSION[$this->sessionHost][$this->sessionName][$key];
        }

        return $defaultValue;
    }

    public function getSessionUserdata($key, $defaultValue = null)
    {
        $userdata = $this->getSession('userdata', []);

        if (array_key_exists($key, $userdata)) {
            return $userdata[$key];
        }

        return $defaultValue;
    }

    public function setAlias($value)
    {
        if (!array_key_exists($this->sessionHost, $_SESSION)) {
            $_SESSION[$this->sessionHost]['alias'] = null;
        }

        $_SESSION[$this->sessionHost]['alias'] = $value;
    }

    public function getAlias()
    {
        if (array_key_exists($this->sessionHost, $_SESSION) and array_key_exists('alias', $_SESSION[$this->sessionHost])) {
            return $_SESSION[$this->sessionHost]['alias'];
        }

        return null;
    }
}
