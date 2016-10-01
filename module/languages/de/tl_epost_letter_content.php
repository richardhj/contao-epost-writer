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


/**
 * Operations
 */
$GLOBALS['TL_LANG'][$table]['new'][0] = 'Neues Inhaltselement';
$GLOBALS['TL_LANG'][$table]['new'][1] = 'Ein neues Inhaltselement erstellen';
$GLOBALS['TL_LANG'][$table]['edit'][0] = 'Inhaltselement bearbeiten';
$GLOBALS['TL_LANG'][$table]['edit'][1] = 'Inhaltselement ID %s bearbeiten';
$GLOBALS['TL_LANG'][$table]['editheader'][0] = 'Brief-Einstellungen bearbeiten';
$GLOBALS['TL_LANG'][$table]['editheader'][1] = 'Die Brief-Einstellungen bearbeiten';
$GLOBALS['TL_LANG'][$table]['copy'][0] = 'Inhaltselement duplizieren';
$GLOBALS['TL_LANG'][$table]['copy'][1] = 'Inhaltselement ID %s duplizieren';
$GLOBALS['TL_LANG'][$table]['cut'][0] = 'Inhaltselement verschieben';
$GLOBALS['TL_LANG'][$table]['cut'][1] = 'Inhaltselement ID %s verschieben';
$GLOBALS['TL_LANG'][$table]['delete'][0] = 'Inhaltselement löschen';
$GLOBALS['TL_LANG'][$table]['delete'][1] = 'Inhaltselement ID %s löschen';
$GLOBALS['TL_LANG'][$table]['toggle'][0] = 'Inhaltselement umschalten';
$GLOBALS['TL_LANG'][$table]['toggle'][1] = 'Inhaltselement ID %s anzeigen/verbergen';
$GLOBALS['TL_LANG'][$table]['show'][0] = 'Details';
$GLOBALS['TL_LANG'][$table]['show'][1] = 'Die Details des Inhaltselements ID %s anzeigen';
