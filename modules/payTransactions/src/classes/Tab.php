<?php

namespace module\classes;

use \module\helpers\RenderHelper;

class Tab
{
    public $title = '';
    public $description = '';
    public $orderPosition = 0;

    public function __get($property)
    {
        if ($property === 'tabName') {
            return lcfirst(preg_replace('/(.*)(Class)$/i', '$1', get_called_class()));
        }
    }

    public function getTabName()
    {
        return lcfirst(pathinfo(str_replace(['\\', '/'], '/', get_called_class()))['filename']);
    }

    protected function render($view, $properties = [])
    {
        $tabName = $this->getTabName();

        $templatePath = realpath(dirname(__FILE__) . "/../views/{$tabName}/{$view}.php");

        $properties = array_merge([
            'tabName' => $this->getTabName(),
        ], $properties);

        return RenderHelper::renderTemplate($templatePath, $properties);
    }

    public function actionIndex()
    {
        return null;
    }
}
