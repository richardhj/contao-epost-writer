<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\AvisotaBridge\Message;


use Avisota\Message\MessageInterface;
use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;


class EPostMessage implements MessageInterface
{

    /**
     * @var Envelope
     */
    protected $envelope;


    /**
     * @var DeliveryOptions
     */
    protected $deliveryOptions;


    /**
     * @var string[]
     */
    protected $attachments;


    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([$this->envelope, $this->deliveryOptions, $this->attachments]);
    }


    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->envelope, $this->deliveryOptions, $this->attachments) = unserialize($serialized);
    }


    /**
     * @return array
     */
    public function getRecipients()
    {
        // TODO: Implement getRecipients() method.
        return (null !== $this->envelope) ? $this->envelope->getRecipients() : null;
    }


    /**
     * @return array
     */
    public function getCopyRecipients()
    {
        // TODO: Implement getCopyRecipients() method.
    }


    /**
     * @return array
     */
    public function getBlindCopyRecipients()
    {
        // TODO: Implement getBlindCopyRecipients() method.
    }


    /**
     * @return string
     */
    public function getFrom()
    {
        // TODO: Implement getFrom() method.
    }


    /**
     * @return string
     */
    public function getSender()
    {
        // TODO: Implement getSender() method.
    }


    /**
     * @return string
     */
    public function getReplyTo()
    {
        // TODO: Implement getReplyTo() method.
    }


    /**
     * @return string
     */
    public function getSubject()
    {
        // TODO: Implement getSubject() method.
    }


    /**
     * @param Envelope $envelope
     *
     * @return EPostMessage
     */
    public function setEnvelope($envelope)
    {
        $this->envelope = $envelope;

        return $this;
    }


    /**
     * @return Envelope
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }


    /**
     * @param DeliveryOptions $deliveryOptions
     *
     * @return self
     */
    public function setDeliveryOptions($deliveryOptions)
    {
        $this->deliveryOptions = $deliveryOptions;

        return $this;
    }


    /**
     * @return DeliveryOptions
     */
    public function getDeliveryOptions()
    {
        return $this->deliveryOptions;
    }


    /**
     * @param \string[] $attachments
     *
     * @return self
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }


    /**
     * @return \string[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }


    /**
     * @param $attachment
     *
     * @return self
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }
}
