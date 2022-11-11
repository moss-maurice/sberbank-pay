<?php

namespace mmaurice\sberpay\classes;

use \mmaurice\modx\Search;
use \mmaurice\sberpay\classes\Plugin;

class Installer
{
    protected $search;

    public function __construct($properties = array())
    {
        $this->search = new Search;

        $stage = 0;

        if (isset($_POST['stage'])) {
            $stage = trim($_POST['stage']);
        }

        $stageName = "stage{$stage}";

        if (method_exists($this, $stageName)) {
            return $this->$stageName();
        }

        $this->stage0();
    }

    protected function stage0()
    {
        $html = "<form method=\"POST\">
                <p>Добро пожаловать в мастер установки плагина <strong>" . Plugin::PLUGIN_CONTAINER . " v." . Plugin::PLUGIN_VERSION . "</strong></p>
                <p>Если вы готовы к установке, нажмите кнопку <strong>Начать</strong>.</p>
                <input type=\"hidden\" name=\"stage\" value=\"1\" />
                <br />
                <br />
                <button>Начать</button>
            </form>";

        return $this->draw('Приветствие', $html);
    }

    protected function stage1()
    {
        $options = array_map(function ($row) {
            return "<option value=\"{$row['id']}\">{$row['id']}. {$row['templatename']}</option>" . PHP_EOL . "                    ";
        }, $this->search->getList([
            'select' => [
                "st.id",
                "st.templatename",
            ],
            'from' => $this->search->getFullTableName('site_templates'),
            'alias' => "st",
        ]));

        $html = "<form method=\"POST\">
                <label>Заголовок страницы:</label>
                <input type=\"text\" name=\"pagetitle\" value=\"Онлайн-оплата\" placeholder=\"Онлайн-оплата\" required />
                <label>Псевдоним страницы:</label>
                <input type=\"text\" name=\"alias\" value=\"online-oplata\" placeholder=\"online-oplata\" required />
                <label>Шаблон страницы:</label>
                <select name=\"template\" required>
                    <option value=\"0\" selected>Не выбрано</option>
                    " . implode("", $options) . "
                </select>
                <input type=\"hidden\" name=\"stage\" value=\"2\" />
                <br />
                <br />
                <br />
                <button>Создать</button>
                <p class=\"message\">Если страница уже есть, вы можете <a href=\"#\">пропустить</a> этот шаг.</p>
            </form>";

            // Если мы делаем пропуск страницы, то мы должны перейти на интерфейс, где мы выберем существующую страницу из списка

        return $this->draw('Шаг 1 :: Создание страницы оплаты', $html);
    }

    protected function stage2()
    {
        if (!empty($_POST)) {
            $pagetitle = (isset($_POST['pagetitle']) && !empty($_POST['pagetitle'])) ? trim($_POST['pagetitle']) : 'Онлайн-оплата';
            $alias = (isset($_POST['alias']) && !empty($_POST['alias'])) ? trim($_POST['alias']) : 'online-oplata';
            $template = (isset($_POST['template']) && !empty($_POST['template'])) ? intval($_POST['template']) : 0;

            $fields = [
                'pagetitle' => $pagetitle,
                'alias' => $alias,
                'published' => 1,
                'content' => '{{payButton}}',
                'template' => $template,
                'searchable' => 0,
                'cacheable' => 0,
                'createdby' => 1,
                'createdon' => time(),
                'editedby' => 1,
                'editedon' => time(),
                'publishedby' => 1,
                'publishedon' => time(),
                'hidemenu' => 1,
            ];

            $sql = "INSERT
                INTO {$this->search->getFullTableName('site_content')}
                    (`" . implode("`, `", array_keys($fields)) . "`)
                VALUES
                    ('" . implode("', '", array_values($fields)) . "');";

            if ($this->search->query($sql)) {
                $pageId = $this->search->getInsertId();

                // Тут мы получаем id созданной страницы. Не мешало бы поместить его в сессию.
                // Весь код POST лучше перенести в stage1, потому что это должно быть там. + добавить вызов метода stage2 после сохранения.
                // И вообще пока не сохранять. А либо указать данные для новой страницы, либо выбрать существующую. А создавать всё только на последнем шаге.

                // А тут нужно вывести форму настроек для плагина.
            }
        }

        return $this->draw('Шаг 2 :: Создание страницы оплаты', $html);
    }

    protected function draw($title, $body = '')
    {
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"utf-8\" />
        <title>Installer :: {$title}</title>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Roboto:300);

            .login-page {
                width: 800px;
                padding: 8% 0 0;
                margin: auto;
            }
            .form {
                position: relative;
                z-index: 1;
                background-color: #ffffff;
                max-width: 800px;
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                border-radius: 10px;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input,
            .form textarea,
            .form select {
                font-family: \"Roboto\", sans-serif;
                outline: 0;
                background-color: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 14px;
                border-radius: 3px;
            }
            .form select {
                background-image: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50px' height='50px'><polyline points='46.139,15.518 25.166,36.49 4.193,15.519'/></svg>\");
                background-color: #f2f2f2;
                background-repeat: no-repeat;
                background-position: right 10px top 19px;
                background-size: 8px 8px;
                padding-right: 30px;
                text-align: left;
                -webkit-appearance: none;
                -moz-appearance: none;
                -webkit-transition: 0.3s ease all;
                -moz-transition: 0.3s ease all;
                -ms-transition: 0.3s ease all;
                -o-transition: 0.3s ease all;
                transition: 0.3s ease all;
                -moz-appearance: none;
                text-indent: 0.01px;
            }
            .form select::-ms-expand {
                display: none;
            }
            .form select:focus,
            .form select:active {
                border:0;
                outline:0;
            }
            .form select option:focus {
                background: #e67e22;
            }
            .form select option {
                background-color: #ecf0f1;
                border-radius: 0;
                padding: 20px;
                display: block;
            }
            .form button {
                font-family: \"Roboto\", sans-serif;
                text-transform: uppercase;
                outline: 0;
                background-color: #e67e22;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #ffffff;
                font-size: 14px;
                -webkit-transition: all 0.3 ease;
                transition: all 0.3 ease;
                cursor: pointer;
                border-radius: 3px;
            }
            .form button:hover,
            .form button:active,
            .form button:focus {
                background-color: #d35400;
            }
            .form .message {
                margin: 15px 0 0;
                color: #b3b3b3;
                font-size: 12px;
                text-align: center;
            }
            .form .message a {
                color: #e67e22;
                text-decoration: none;
            }
            .form .register-form {
                display: none;
            }
            .container {
                position: relative;
                z-index: 1;
                max-width: 300px;
                margin: 0 auto;
            }
            .container:before, .container:after {
                content: \"\";
                display: block;
                clear: both;
            }
            .container .info {
                margin: 50px auto;
                text-align: center;
            }
            .container .info h1 {
                margin: 0 0 15px;
                padding: 0;
                font-size: 36px;
                font-weight: 300;
                color: #1a1a1a;
            }
            .container .info span {
                color: #4d4d4d;
                font-size: 12px;
            }
            .container .info span a {
                color: #000000;
                text-decoration: none;
            }
            .container .info span .fa {
                color: #EF3B3A;
            }
            form {
                text-align: left;
            }
            form label {
                font-weight: 500;
                padding: 0 0 10px 0;
                display: block;
            }
            body {
                background-color: #82ccdd; /* fallback for old browsers */
                background: -webkit-linear-gradient(right, #82ccdd, #b8e994);
                background: -moz-linear-gradient(right, #82ccdd, #b8e994);
                background: -o-linear-gradient(right, #82ccdd, #b8e994);
                background: linear-gradient(to left, #82ccdd, #b8e994);
                font-family: \"Roboto\", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
    </head>
    <body>
        <div class=\"login-page\">
            <div class=\"form\">
                <h2>{$title}</h2>
                <br />
                {$body}
            </div>
        </div>
    </body>
</html>";

        die();
    }
}
