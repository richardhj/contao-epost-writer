<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 * Copyright (c) 2015-2016 Richard Henkenjohann
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

/**
 * Back end modules
 */
array_insert(
    $GLOBALS['BE_MOD']['epost'],
    0,
    [
        'epost_writer' => [
            'tables'      => [EPost\Model\Letter::getTable(), EPost\Model\LetterContent::getTable()],
            'icon'        => 'assets/epost/writer/images/writer.png',
            'stylesheet'  => 'assets/epost/writer/css/backend.css',
            'send_letter' => ['EPost\Dca\Writer', 'sendLetter'],
        ],
        'epost_outbox' => [
            'callback'   => 'EPost\Backend\Outbox',
            'icon'       => 'assets/epost/writer/images/outbox.png',
            'stylesheet' => 'assets/epost/writer/css/backend.css',
        ],
    ]
);

$GLOBALS['BE_MOD']['epost']['epost_templates'] = [
    'tables'     => [EPost\Model\Template::getTable()],
    'icon'       => 'assets/epost/writer/images/templates.png',
    'stylesheet' => 'assets/epost/writer/css/backend.css',
    'nested'     => 'epost_config',
];

/**
 * Models
 */
$GLOBALS['TL_MODELS'][EPost\Model\Letter::getTable()] = 'EPost\Model\Letter';
$GLOBALS['TL_MODELS'][EPost\Model\LetterContent::getTable()] = 'EPost\Model\LetterContent';
$GLOBALS['TL_MODELS'][EPost\Model\Template::getTable()] = 'EPost\Model\Template';
