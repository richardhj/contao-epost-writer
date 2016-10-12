<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\AvisotaBridge\Template;


use Avisota\Recipient\RecipientInterface;
use Avisota\Templating\MessageTemplateInterface;
use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;
use EPost\AvisotaBridge\Message\EPostMessage;
use EPost\AvisotaBridge\Recipient\EPostDelegate;
use EPost\Model\Letter;
use EPost\Model\Template;


/**
 * Class EPostTemplate
 * @package EPost\AvisotaBridge\Template
 */
class EPostTemplate implements MessageTemplateInterface
{

    /**
     * Render a message for the given recipient.
     *
     * @param EPostDelegate|RecipientInterface $recipient
     * @param array                            $newsletterData Additional newsletter data.
     *
     * @return \Avisota\Message\MessageInterface
     * @internal param RecipientInterface $recipientEmail The main recipient.
     */
    public function render(RecipientInterface $recipient = null, array $newsletterData = array())
    {
        $envelope = new Envelope();
        $letterType = Envelope::LETTER_TYPE_HYBRID;
        $message = new EPostMessage();
//     $message->setRecipient($recipient->getOrigin());

        /** @var Letter $letter */
        $letter = $newsletterData['letter'];
        /** @var Template $template */
        $template = $letter->getRelated('template');
        $pdf = $template->createLetterAsPdf($letter, $recipient->getOrigin());
        $pdfPath = TL_ROOT.'/system/tmp/'.uniqid();
        $pdf->Output($pdfPath, 'F');

        $message->addAttachment($pdfPath);

        $envelope
            ->setSystemMessageType($letterType)//            ->setSubject('Test')
        ;

        $message->setEnvelope($envelope);

        // Set recipients and delivery options
        switch ($message->getEnvelope()->getSystemMessageType()) {
            // Hybrid letter
            case Envelope::LETTER_TYPE_HYBRID:
                // Add recipients
                $envelope->addRecipientPrinted($recipient->getOrigin());

                // Set delivery options
                $deliveryOptions = new DeliveryOptions();
                $deliveryOptions
                    ->setRegistered($template->registered)
                    ->setColor($template->color)
                    ->setCoverLetterIncluded();

                $message->setDeliveryOptions($deliveryOptions);

                break;

            // Normal letter
            case Envelope::LETTER_TYPE_NORMAL:
                // Add recipients
                $envelope->addRecipientNormal($recipient->getOrigin());

                break;
        }

        return $message;
    }
}
