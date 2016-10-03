<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


$table = EPost\Model\Letter::getTable();


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend'] = 'Allgemeine Einstellungen';
$GLOBALS['TL_LANG'][$table]['recipients_legend'] = 'Empfänger';
$GLOBALS['TL_LANG'][$table]['attachments_legend'] = 'Dateianhänge';


/**
 * Operations
 */
$GLOBALS['TL_LANG'][$table]['new'][0] = 'Neuer Brief';
$GLOBALS['TL_LANG'][$table]['new'][1] = 'Einen neuen Brief erstellen';
$GLOBALS['TL_LANG'][$table]['edit'][0] = 'Brief-Inhaltselemente';
$GLOBALS['TL_LANG'][$table]['edit'][1] = 'Inhaltselemente von Brief ID %s bearbeiten';
$GLOBALS['TL_LANG'][$table]['editheader'][0] = 'Brief bearbeiten';
$GLOBALS['TL_LANG'][$table]['editheader'][1] = 'Brief ID %s bearbeiten';
$GLOBALS['TL_LANG'][$table]['copy'][0] = 'Brief duplizieren';
$GLOBALS['TL_LANG'][$table]['copy'][1] = 'Brief ID %s duplizieren';
$GLOBALS['TL_LANG'][$table]['delete'][0] = 'Brief löschen';
$GLOBALS['TL_LANG'][$table]['delete'][1] = 'Brief ID %s löschen';
$GLOBALS['TL_LANG'][$table]['show'][0] = 'Details';
$GLOBALS['TL_LANG'][$table]['show'][1] = 'Die Details des Briefes ID %s anzeigen';
$GLOBALS['TL_LANG'][$table]['send_letter'][0] = 'Brief versenden';
$GLOBALS['TL_LANG'][$table]['send_letter'][1] = 'Den Brief ID %s versenden';


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
$GLOBALS['TL_LANG'][$table]['attachments'][0] = 'Anhänge';
$GLOBALS['TL_LANG'][$table]['attachments'][1] = 'Wählen Sie die Anhänge aus, die dem Schreiben beigefügt werden sollen.';
$GLOBALS['TL_LANG'][$table]['attachments_upload'][0] = 'Anhänge hochladen';
$GLOBALS['TL_LANG'][$table]['attachments_upload'][1] = 'Laden Sie zusätzlich Anhänge hoch, die dem Schreiben beigefügt werden sollen.';
