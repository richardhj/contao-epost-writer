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
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['recipients_legend'] = 'Empfänger';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['title'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['title'][1] = 'Geben Sie einen intern verwendeten Namen ein.';
$GLOBALS['TL_LANG'][$table]['template'][0] = 'Template';
$GLOBALS['TL_LANG'][$table]['template'][1] = 'Wählen Sie das Template aus, welches verwendet werden soll.';
$GLOBALS['TL_LANG'][$table]['send_to_member_group'][0] = 'An Mitgliedergruppen senden';
$GLOBALS['TL_LANG'][$table]['send_to_member_group'][1] = '';
$GLOBALS['TL_LANG'][$table]['send_to_member'][0] = 'An einzelne Mitglieder senden';
$GLOBALS['TL_LANG'][$table]['send_to_member'][1] = '';
$GLOBALS['TL_LANG'][$table]['recipients_member_group'][0] = 'Mitgliedergruppen';
$GLOBALS['TL_LANG'][$table]['recipients_member_group'][1] = '';
$GLOBALS['TL_LANG'][$table]['recipients_member'][0] = 'Mitglieder';
$GLOBALS['TL_LANG'][$table]['recipients_member'][1] = '';
