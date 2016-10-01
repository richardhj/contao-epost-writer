<?php
/**
 * FERIENPASS extension for Contao Open Source CMS built on the MetaModels extension
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package Ferienpass
 * @author  Richard Henkenjohann <richard@ferienpass.online>
 */

namespace EPost\Helper;


class Config
{

    const CONFIG_PREFIX = 'epost_';


    const QUEUE_MAX_SEND_TIME = 'queue:maxSendTime';


    const QUEUE_MAX_SEND_COUNT = 'queue:maxSendCount';


    const QUEUE_CYCLE_PAUSE = 'queue:cyclePause';


    const TRANSPORT_USER = 'transport:user';


    /**
     * Return a configuration value
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public static function get($key)
    {
        return \Contao\Config::get(static::CONFIG_PREFIX.$key);
    }


    /**
     * Set and save a configuration value
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        \Contao\Config::persist(static::CONFIG_PREFIX.$key, $value);
    }
}
