<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


$table = EPost\Model\Template::getTable();


/**
 * Table tl_article
 */
$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config'       => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list'         => [
        'sorting'           =>
            [
                'mode'        => 1,
                'fields'      => ['name'],
                'flag'        => 1,
                'panelLayout' => 'search',
            ],
        'label'             =>
            [
                'fields' => ['name'],
            ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG'][$table]['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
//                'button_callback' => [$table, 'editHeader'],
            ],
            'copy'   => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['copy'],
                'href'       => 'act=paste&amp;mode=copy',
                'icon'       => 'copy.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'copyArticle'],
            ],
            'cut'    => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'cutArticle'],
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'deleteArticle'],
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG'][$table]['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],

    // MetaPalettes
    'metapalettes' => [
        'default' => [
            'title'    => [
                'name',
            ],
            'template' => [
                'documentTpl',
                'margin',
            ],
            'expert'   => [
                ':hide',
                'parseSender',
            ],
        ],
    ],

    // Fields
    'fields'       => [
        'id'            =>
            [
                'label'  => ['ID'],
                'search' => true,
                'sql'    => "int(10) unsigned NOT NULL auto_increment",
            ],
        'tstamp'        =>
            [
                'sql' => "int(10) unsigned NOT NULL default '0'",
            ],
        'title'         => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['title'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => [
                'mandatory'      => true,
                'decodeEntities' => true,
                'maxlength'      => 255,
            ],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'documentTitle' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['documentTitle'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'mandatory'      => true,
                'decodeEntities' => true,
                'maxlength'      => 255,
                'tl_class'       => 'w50',
            ],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'fileTitle'     => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['fileTitle'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'mandatory'      => true,
                'decodeEntities' => true,
                'maxlength'      => 255,
                'tl_class'       => 'w50',
            ],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'documentTpl'   => [
            'label'            => &$GLOBALS['TL_LANG'][$table]['documentTpl'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => function (\DataContainer $dc) {
                return \Controller::getTemplateGroup('epost_template_');
            },
            'eval'             => [
//                'includeBlankOption' => true,
                'chosen'    => true,
                'tl_class'  => 'w50',
                'mandatory' => true,
            ],
            'sql'              => "varchar(64) NOT NULL default ''",
        ],
        'margin'        => [
            'label'         => &$GLOBALS['TL_LANG'][$table]['margin'],
            'inputType'     => 'trbl',
            'options'       => ['mm'],
            'eval'          => [
                'rgxp'     => 'digit_auto_inherit',
                'tl_class' => 'w50',
            ],
            'save_callback' => [
                function ($value) {
                    $values = deserialize($value);

                    $validation = [
                        'left'   => 12,
                        'top'    => 2,
                        'right'  => 2,
                        'bottom' => 2,
                    ];

                    foreach ($values as $k => $v) {
                        if (array_key_exists($k, $validation) && strlen($v) && $v < $validation[$k]) {
                            throw new \Exception(
                                sprintf('The margin "%s" must not go below %u%s', $k, $validation[$k], $values['unit'])
                            );
                        }
                    }

                    return $value;
                },
            ],
            'sql'           => "varchar(128) NOT NULL default ''",
        ],
        'parseSender'   => [
            'label'         => &$GLOBALS['TL_LANG'][$table]['parseSender'],
            'exclude'       => true,
            'inputType'     => 'textarea',
            'eval'          => [
                'tl_class'       => 'long clr',
                'mandatory'      => true,
                'decodeEntities' => true,
            ],
            'load_callback' => [
                function ($value, \DataContainer $dc) {
                    if (!$value) {
                        return <<<'TXT'
{if sender_company==""}##sender_firstName## ##sender_lastName##{else}##sender_company##{endif}, ##sender_streetName##{if sender_houseNumber==""}{else} ##sender_houseNumber##{endif}, ##sender_zipCode## ##sender_city##
TXT;
                    }

                    return $value;
                },
            ],
            'sql'           => "text NULL",
        ],
    ],
];