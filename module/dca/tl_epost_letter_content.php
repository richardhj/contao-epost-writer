<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


$table = EPost\Model\LetterContent::getTable();
$table2 = ContentModel::getTable();
Controller::loadDataContainer($table2);
Controller::loadLanguageFile($table2);

/**
 * Table tl_content
 */
$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'ptable'           => EPost\Model\Letter::getTable(),
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list'        => [
        'sorting'           => [
            'mode'                  => 4,
            'fields'                => ['sorting'],
            'panelLayout'           => 'filter;search,limit',
            'headerFields'          => [
                'title',
                'tstamp',
            ],
            'child_record_callback' => ['EPost\Dca\Writer', 'parseLetterContentElement'],
        ],
        'global_operations' => [
            'send_letter' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['send_letter'],
                'href'       => 'key=send_letter',
                'class'      => 'header_icon header_send_epost_letter',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'all'         => [
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
            ],
            'copy'   => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['copy'],
                'href'       => 'act=paste&amp;mode=copy',
                'icon'       => 'copy.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'cut'    => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'deleteElement'],
            ],
            'toggle' => [
                'label'                => &$GLOBALS['TL_LANG'][$table]['toggle'],
                'icon'                 => 'visible.gif',
                'attributes'           => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'haste_ajax_operation' => [
                    'field'   => 'invisible',
                    'options' => [
                        [
                            'value' => '1',
                            'icon'  => 'invisible.gif',
                        ],
                        [
                            'value' => '',
                            'icon'  => 'visible.gif',
                        ],
                    ],
                ],
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG'][$table]['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes'    => array_intersect_key(
        $GLOBALS['TL_DCA'][$table2]['palettes'],
        array_flip(
            [
                '__selector__',
                'default',
                'headline',
                'text',
                'html',
                'list',
                'table',
                'image',
                'gallery',
            ]
        )
    ),

    // Subpalettes
    'subpalettes' => array_intersect_key(
        $GLOBALS['TL_DCA'][$table2]['subpalettes'],
        array_flip(
            [
                'addImage',
                'sortable',
                'useImage',
            ]
        )
    ),

    // Fields
    'fields'      => array_merge_recursive(
        array_intersect_key(
            $GLOBALS['TL_DCA'][$table2]['fields'],
            array_flip(
                [
                    'id',
                    'pid',
                    'sorting',
                    'tstamp',
                    'type',
                    'headline',
                    'text',
                    'addImage',
                    'singleSRC',
                    'size',
                    'imagemargin',
                    'imageUrl',
                    'caption',
                    'floating',
                    'html',
                    'listtype',
                    'listitems',
                    'tableitems',
                    'thead',
                    'tfoot',
                    'tleft',
                    'multiSRC',
                    'orderSRC',
                    'perRow',
                    'numberOfItems',
                    'sortBy',
                    'metaIgnore',
                    'galleryTpl',
                    'customTpl',
                    'cssID',
                    'space',
                    'invisible',
                ]
            )

        ),
        [
            'type' => [
                'options_callback' => function () {
                    return [
                        'asf',
                    ];
                },
            ],
        ]
    ),
];
