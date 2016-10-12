<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

use EPost\Model\WriterConfig;


$table = WriterConfig::getTable();

$GLOBALS['TL_DCA'][$table] = [
    // Config
    'config' => [
        'dataContainer' => 'General',
        'forceEdit'     => true,
    ],

    'dca_config'   => [
        'data_provider' => [
            'default' => [
                'class' => 'DcGeneral\Data\SingleModelDataProvider',
            ],
        ],
        'view'          => 'DcGeneral\View\SingleModelView',
    ],

    // Metapalettes
    'metapalettes' =>
        [
            'default' => [
                'transport' => [
                    'transport_user',
                ],
                'queue'     => [
                    'queue_cycle_pause',
                    'queue_max_send_count',
                    'queue_max_send_time',
                ],
            ],
        ],

    // Fields
    'fields'       => [
        'transport_user'       => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['transport_user'],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        'queue_cycle_pause'    => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['queue_cycle_pause'],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        'queue_max_send_count' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['queue_max_send_count'],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
        'queue_max_send_time'  => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['queue_max_send_time'],
            'inputType' => 'text',
            'eval'      => [
                'mandatory' => true,
                'tl_class'  => 'w50',
            ],
        ],
    ],

];
