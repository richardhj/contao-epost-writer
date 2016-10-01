<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the templates
 */
TemplateLoader::addFiles(
    [
        'epost_template_default'       => 'system/modules/epost-writer/templates',
        'epost_addressblock_recipient' => 'system/modules/epost-writer/templates',
        'be_epost_outbox'              => 'system/modules/epost-writer/templates/backend',
    ]
);
