<?php

namespace mmaurice\sberpay\classes;

class Logger
{
    protected $enableLog;

    public function __construct($enableLog = true)
    {
        $this->enableLog = $enableLog;
    }

    public function writeLog($message, $name = '', $path = null)
    {
        if ($this->enableLog) {
            if (is_null($path)) {
                $path = realpath(dirname(__FILE__) . '/../logs') . '/';
            }

            return file_put_contents($path . (!empty($name) ? $name . '_' : '') . date('Y-m-d_H-i-s') . '_' . md5(microtime()) . '.log', $message);
        }

        return false;
    }
}
