<?php

namespace module\helpers;

use \Exception;
use \mmaurice\modx\Search;
use \mmaurice\qurl\Client;

class RenderHelper
{
    static public function renderTemplate($__tplName__, $__variables__ = [])
    {
        try {
            $__tplPath__ = realpath($__tplName__);

            if (!file_exists($__tplPath__) or !is_file($__tplPath__)) {
                throw new Exception('Template file "' . $__tplName__ . '" is not found!');
            }

            $__variables__['modulePath'] = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . '/..'))), '/');

            if (is_array($__variables__) and !empty($__variables__)) {
                extract($__variables__, EXTR_PREFIX_SAME, 'data');
            } else {
                $data = $__variables__;
            }

            ob_start();
            ob_implicit_flush(false);

            include($__tplPath__);

            $content = ob_get_clean();

            return $content;
        } catch (Exception $exceptiob) {
            echo $exceptiob->getMessage();
        }
    }
}
