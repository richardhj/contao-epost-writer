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
/** @noinspection PhpUndefinedMethodInspection */
$table2 = ContentModel::getTable();

\Controller::loadLanguageFile($table2);

foreach ($GLOBALS['TL_LANG'][$table2] as $k => $v) {
    if (is_array($v) && !in_array($k, ['new', 'edit', 'copy', 'cut', 'delete', 'toggle', 'show', 'pastenew'])) {
        continue;
    }

    $GLOBALS['TL_LANG'][$table][$k] = $v;
}

$GLOBALS['TL_LANG'][$table]['editheader'][0] = 'Brief-Einstellungen bearbeiten';
$GLOBALS['TL_LANG'][$table]['editheader'][1] = 'Die Brief-Einstellungen bearbeiten';
