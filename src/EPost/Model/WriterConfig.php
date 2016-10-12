<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Model;


/**
 * Class WriterConfig
 * @property int   $transport_user
 * @property int   $queue_cycle_pause
 * @property int   $queue_max_send_count
 * @property int   $queue_max_send_time
 * @property mixed $writer_attachments_path
 * @package EPost\Model
 */
class WriterConfig extends AbstractSingleModel
{

    /**
     * {@inheritdoc}
     */
    protected static $strTable = 'tl_epost_writer_config';


    /**
     * {@inheritdoc}
     */
    protected static $objInstance;
}
