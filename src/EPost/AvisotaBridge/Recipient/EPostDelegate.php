<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\AvisotaBridge\Recipient;


use Avisota\Recipient\RecipientInterface;
use EPost\Api\Metadata\Envelope\AbstractRecipient;
use EPost\Api\Metadata\Envelope\Recipient\Normal;


/**
 * Class EPostDelegate
 * @package EPost\AvisotaBridge\Recipient
 */
class EPostDelegate implements RecipientInterface
{

    /**
     * @var AbstractRecipient
     */
    protected $origin;


    public static function createForOrigin(AbstractRecipient $origin)
    {
        $instance = new self;

        return $instance->setOrigin($origin);
    }


    /**
     * Get the recipient email address.
     *
     * @return string
     */
    public function getEmail()
    {
        if ($this->origin instanceof Normal) {
            return $this->origin->getEpostAddress();
        } else {
            return null;
        }
    }


    /**
     * Check if this recipient has personal data.
     *
     * @return bool
     */
    public function hasDetails()
    {
        return count($this->origin->getData());
    }


    /**
     * Get a single personal data field value.
     * Return null if the field does not exists.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->origin->$name;
    }


    /**
     * Get all personal data values as associative array.
     *
     * The personal data must have a key 'email', that contains the email address.
     * <pre>
     * array (
     *     'email' => '...',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getDetails()
    {
        return $this->origin->getData();
    }


    /**
     * Get all personal data keys.
     *
     * The keys must contain 'email'.
     * <pre>
     * array (
     *     'email',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->origin->getData());
    }


    /**
     * @param \EPost\Api\Metadata\Envelope\AbstractRecipient $origin
     *
     * @return EPostDelegate
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }


    /**
     * @return \EPost\Api\Metadata\Envelope\AbstractRecipient
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}