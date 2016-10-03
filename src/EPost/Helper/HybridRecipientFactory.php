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


use EPost\Api\Metadata\Envelope\Recipient\Hybrid as HybridRecipient;


/**
 * Class HybridRecipientFactory
 * @package EPost\Helper
 */
class HybridRecipientFactory
{

    /**
     * Create an instance by a given data array
     *
     * @param array $data
     *
     * @return HybridRecipient
     */
    public static function create(array $data)
    {
        $recipient = new HybridRecipient();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'company':
                    $recipient->setCompany($value);
                    break;

                case 'firstname':
                    $recipient->setFirstName($value);
                    break;

                case 'lastname':
                    $recipient->setLastName($value);
                    break;

                case 'street':
                    $recipient->setStreetName($value);
                    break;

                case 'postal':
                    $recipient->setZipCode($value);
                    break;

                case 'city':
                    $recipient->setCity($value);
                    break;
            }
        }

        return $recipient;
    }


    /**
     * Create an instance by a given member model
     *
     * @param \MemberModel|\Model $model
     *
     * @return HybridRecipient
     */
    public static function createFromModel(\MemberModel $model)
    {
        return self::create($model->row());
    }
}
