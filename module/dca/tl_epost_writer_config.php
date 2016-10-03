<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

use EPost\Helper\Config;


$table = 'tl_epost_writer_config';

$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config'       => [
        'dataContainer' => 'General',
        'sql'           => [
            'keys' => [
                'key' => 'primary',
            ],
        ],
    ],

    // Metapalettes
    'metapalettes' =>
        [
            'default' => [
                'transport' => [
                    Config::TRANSPORT_USER,
                ],
                'queue'     => [
                    Config::QUEUE_CYCLE_PAUSE,
                    Config::QUEUE_MAX_SEND_COUNT,
                    Config::QUEUE_MAX_SEND_TIME,
                ],
            ],
        ],

    // Fields
    'fields'       => [
        Config::TRANSPORT_USER       => [
            'label'     => &$GLOBALS['TL_LANG'][$table][Config::TRANSPORT_USER],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        Config::QUEUE_CYCLE_PAUSE    => [
            'label'     => &$GLOBALS['TL_LANG'][$table][Config::QUEUE_CYCLE_PAUSE],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        Config::QUEUE_MAX_SEND_COUNT => [
            'label'     => &$GLOBALS['TL_LANG'][$table][Config::QUEUE_MAX_SEND_COUNT],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        Config::QUEUE_MAX_SEND_TIME  => [
            'label'     => &$GLOBALS['TL_LANG'][$table][Config::QUEUE_MAX_SEND_TIME],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
    ],

];
