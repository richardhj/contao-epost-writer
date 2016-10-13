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


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['transport_legend'] = 'Versand-Einstellungen';
$GLOBALS['TL_LANG'][$table]['queue_legend'] = 'Warteschlange-Einstellungen';
$GLOBALS['TL_LANG'][$table]['writer_legend'] = 'Writer-Einstellungen';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['transport_user'][0] = 'Versand-API-Benutzer';
$GLOBALS['TL_LANG'][$table]['transport_user'][1] = 'Wählen Sie den API-Benutzer aus, mit dem die Briefe versendet werden sollen';
$GLOBALS['TL_LANG'][$table]['queue_cycle_pause'][0] = 'Zyklen-Pause';
$GLOBALS['TL_LANG'][$table]['queue_cycle_pause'][1] = 'Bitte geben Sie die Zeit in Sekunden an, die zwischen den Sendezyklen liegen soll.';
$GLOBALS['TL_LANG'][$table]['queue_max_send_count'][0] = 'Anzahl der Sendungen';
$GLOBALS['TL_LANG'][$table]['queue_max_send_count'][1] = 'Bitte geben Sie die maximale Anzahl an Sendungen an, die für den Sendezyklus gilt.';
$GLOBALS['TL_LANG'][$table]['queue_max_send_time'][0] = 'Sendedauer';
$GLOBALS['TL_LANG'][$table]['queue_max_send_time'][1] = 'Bitte geben Sie die Maximaldauer in Sekunden an, die dann für jeden Sendezyklus gilt.';
$GLOBALS['TL_LANG'][$table]['writer_attachments_path'][0] = 'Anhänge-Pfad';
$GLOBALS['TL_LANG'][$table]['writer_attachments_path'][1] = 'Wählen Sie einen Ordner aus, in dem die Anhänge automatisch hochgeladen werden sollen.';
