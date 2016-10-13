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
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['transport_user'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['transport_user'][1] = 'Geben';
$GLOBALS['TL_LANG'][$table]['queue_cycle_pause'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['queue_cycle_pause'][1] = 'Geben';
$GLOBALS['TL_LANG'][$table]['queue_max_send_count'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['queue_max_send_count'][1] = 'Geben';
$GLOBALS['TL_LANG'][$table]['queue_max_send_time'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['queue_max_send_time'][1] = 'Geben';
$GLOBALS['TL_LANG'][$table]['writer_attachments_path'][0] = 'Titel';
$GLOBALS['TL_LANG'][$table]['writer_attachments_path'][1] = 'Geben';
