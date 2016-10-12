<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


use EPost\Model\Letter;
use EPost\Model\Template;
use EPost\Model\WriterConfig;


$table = EPost\Model\Letter::getTable();


/**
 * Table tl_article
 */
/** @noinspection PhpUndefinedMethodInspection */
$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config'          => [
        'dataContainer'     => 'Table',
        'ctable'            => [EPost\Model\LetterContent::getTable()],
        'switchToEdit'      => true,
        'enableVersioning'  => true,
        'onsubmit_callback' => [
            function (\DataContainer $dc) {
                $model = Letter::findByPk($dc->id);
                $attachments = deserialize($model->attachments);

                foreach (deserialize($model->attachments_upload, true) as $path) {
                    $attachments[] = Dbafs::addResource($path)->uuid;
                }

                $model->attachments = serialize($attachments);
                $model->attachments_uplaod = '';
                $model->save();
            },
        ],
        'sql'               => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list'            => [
        'sorting'           => [
            'mode'        => 1,
            'fields'      => ['tstamp'],
            'flag'        => 7,
            'panelLayout' => 'search',
        ],
        'label'             => [
            'fields' => ['title'],
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
            'edit'        => [
                'label' => &$GLOBALS['TL_LANG'][$table]['edit'],
                'href'  => 'table='.EPost\Model\LetterContent::getTable(),
                'icon'  => 'edit.gif',
//                'button_callback' => [$table, 'editArticle'],
            ],
            'editheader'  => [
                'label' => &$GLOBALS['TL_LANG'][$table]['editheader'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
//                'button_callback' => [$table, 'editHeader'],
            ],
            'copy'        => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['copy'],
                'href'       => 'act=paste&amp;mode=copy',
                'icon'       => 'copy.gif',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'copyArticle'],
            ],
            'delete'      => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
//                'button_callback' => [$table, 'deleteArticle'],
            ],
            'show'        => [
                'label' => &$GLOBALS['TL_LANG'][$table]['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',

            ],
            'send_letter' => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['send_letter'],
                'href'       => 'key=send_letter',
                'icon'       => 'assets/epost/writer/images/send_letter.png',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
        ],
    ],

    // Select
//    'select' =>
//        [
//        'buttons_callback' =>
//            [
//            [$table, 'addAliasButton']
//            ]
//        ],

    // MetaPalettes
    'metapalettes'    => [
        'default' => [
            'title'       => [
                'title',
                'template',
            ],
//            'config'      => [
//            ],
            'recipients'  => [
                'recipients_member',
                'send_to_member_group',
//                'send_to_member',
//                'exclude_members',
            ],
            'attachments' => [
                'attachments_upload',
                'attachments',
            ],
        ],
    ],

    // MetaSubPalettes
    'metasubpalettes' => [
        'send_to_member_group' => [
            'recipients_member_group',
        ],
//        'send_to_member'       => [
//            'recipients_member',
//        ],
    ],

    // Fields
    'fields'          => [
        'id'                      => [
            'label'  => ['ID'],
            'search' => true,
            'sql'    => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp'                  => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sent'                    => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'                   => [
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
        'template'                => [
            'label'      => &$GLOBALS['TL_LANG'][$table]['template'],
            'exclude'    => true,
            'search'     => true,
            'inputType'  => 'select',
            'foreignKey' => Template::getTable().'.name',
            'eval'       => [
                'mandatory' => true,
                'chosen'    => true,
                'tl_class'  => 'w50',
            ],
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => ['type' => 'hasOne'],
        ],
        'send_to_member'          => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['send_to_member'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'submitOnChange' => true,
                'tl_class'       => 'w50 clr',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'send_to_member_group'    => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['send_to_member_group'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'submitOnChange' => true,
                'tl_class'       => 'w50 clr',
            ],
            'sql'       => "char(1) NOT NULL default ''",
        ],
        'recipients_member'       => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['recipients_member'],
            'exclude'   => true,
            'inputType' => 'tableLookup',
            'eval'      => [
                'foreignTable' => MemberModel::getTable(),
                'fieldType'    => 'checkbox',
                'listFields'   => ['firstname', 'lastname', 'company', 'street', 'city'],
                'searchFields' => ['firstname', 'lastname', 'company', 'street', 'city', 'email'],
                'mandatory'    => true,
                'tl_class'     => 'clr',
            ],
            'sql'       => "text NULL",
            'relation'  => [
                'table' => \MemberModel::getTable(),
                'type'  => 'hasMany',
            ],
        ],
        'recipients_member_group' => [
            'label'            => &$GLOBALS['TL_LANG'][$table]['recipients_member_group'],
            'exclude'          => true,
            'inputType'        => 'checkbox',
            'options_callback' => function () {
                $return = [];
                $groups = \MemberGroupModel::findAll();

                while (null !== $groups && $groups->next()) {
                    $return[$groups->id] = sprintf('%s (ID %u)', $groups->name, $groups->id);
                }

                return $return;
            },
            'eval'             => [
                'mandatory' => true,
                'tl_class'  => 'clr long',
                'multiple'  => true,
            ],
            'sql'              => "text NULL",
            'relation'         => [
                'table' => \MemberModel::getTable(),
                'type'  => 'hasMany',
            ],
        ],
        'attachments'             => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['attachments'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'tl_class'  => 'clr',
                'multiple'  => true,
                'files'     => true,
                'filesOnly' => true,
                'fieldType' => 'checkbox',
            ],
            'sql'       => "blob NULL",
        ],
        'attachments_upload'      => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['attachments_upload'],
            'exclude'   => true,
            'inputType' => 'fileUpload',
            'eval'      => [
                'tl_class'     => 'w50',
                'uploadFolder' => WriterConfig::getInstance()->writer_attachments_path
                    ? FilesModel::findByPk(WriterConfig::getInstance()->writer_attachments_path)->path
                    : null,
            ],
            'sql'       => "text NULL",
        ],
    ],
];

if (null === WriterConfig::getInstance()->writer_attachments_path) {

    unset($GLOBALS['TL_DCA'][$table]['metapalettes']['default']['attachments'][array_search(
            'attachments_upload',
            $GLOBALS['TL_DCA'][$table]['metapalettes']['default']['attachments']
        )]);
}
