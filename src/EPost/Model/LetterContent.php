<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 * Copyright (c) 2015-2016 Richard Henkenjohann
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Model;


use Contao\Model;


class LetterContent extends Model
{

    /**
     * @var string
     */
    protected static $strTable = 'tl_epost_letter_content';


    /**
     * Find all published content elements by their parent ID and parent table
     *
     * @param integer $pid     The article ID
     * @param array   $options An optional options array
     *
     * @return \Model\Collection|\ContentModel|null A collection of models or null if there are no content elements
     */
    public static function findVisibleByPid($pid, array $options = [])
    {
        $t = static::$strTable;

        $columns = ["$t.pid=? AND $t.invisible<>1"];

        if (!isset($options['order'])) {
            $options['order'] = "$t.sorting";
        }

        return static::findBy($columns, [$pid], $options);
    }
}
