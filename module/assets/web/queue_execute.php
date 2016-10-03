<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

function init()
{
    $dir = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);

    while ($dir && $dir != '.' && $dir != '/' && !is_file($dir.'/system/initialize.php')) {
        $dir = dirname($dir);
    }

    if (!is_file($dir.'/system/initialize.php')) {
        header("HTTP/1.0 500 Internal Server Error");
        header('Content-Type: text/html; charset=utf-8');
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>Could not find initialize.php!</p>';
        exit(1);
    }

    define('TL_MODE', 'BE');
    /** @noinspection PhpIncludeInspection */
    require($dir.'/system/initialize.php');

    \BackendUser::getInstance();
}

init();

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$runner = new \EPost\AvisotaBridge\Controller\QueueExecuteController();
$response = $runner->run($request);
$response->send();
