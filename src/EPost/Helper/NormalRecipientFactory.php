<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Helper;


use EPost\Api\Metadata\Envelope\Recipient\Normal as NormalRecipient;


/**
 * Class NormalRecipientFactory
 * @package EPost\Helper
 */
class NormalRecipientFactory
{

    /**
     * Create an instance by a given data array
     *
     * @param array $data
     *
     * @return NormalRecipient
     */
    public static function create(array $data)
    {
        $recipient = new NormalRecipient();

        $recipient->setDisplayName(
            sprintf('%s %s', $data['firstname'], $data['lastname'])
        ); // TODO make this configurable
        $recipient->setEpostAddress($data['email']); // TODO make the field configurable

        return $recipient;
    }


    /**
     * Create an instance by a given member model
     *
     * @param \MemberModel|\Model $model
     *
     * @return NormalRecipient
     */
    public static function createFromModel(\MemberModel $model)
    {
        return self::create($model->row());
    }
}
