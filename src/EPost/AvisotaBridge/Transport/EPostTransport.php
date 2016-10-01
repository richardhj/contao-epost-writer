<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace EPost\AvisotaBridge\Transport;

use Avisota\Message\MessageInterface;
use Avisota\Transport\TransportInterface;
use Avisota\Transport\TransportStatus;
use EPost\Api\Letter;
use EPost\AvisotaBridge\Message\EPostMessage;
use EPost\Model\User;
use GuzzleHttp\Exception\ClientException;
use League\OAuth2\Client\Token\AccessToken;


class EPostTransport implements TransportInterface
{

    /**
     * @var User
     */
    protected $user;


    /**
     * @var AccessToken
     */
    protected $token;


    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Initialise transport.
     *
     * @return void
     */
    public function initialise()
    {
        // Authenticate
        if (false === ($this->token = $this->user->authenticate())) {
            $this->user->redirectForAuthorization('contao/main.php?do=epost_outbox&action=execute');
        }
    }


    /**
     * Transport a message.
     *
     * @param EPostMessage|MessageInterface $message
     *
     * @return TransportStatus
     */
    public function send(MessageInterface $message)
    {
        $letter = new Letter();
        $letter
            ->setTestEnvironment($this->user->test_environment)
            ->setAccessToken($this->token)
            ->setEnvelope($message->getEnvelope());

        if ($letter->getEnvelope()->isHybridLetter()) {
            $letter->setDeliveryOptions($message->getDeliveryOptions());
        }

        // Set attachments
        foreach ($message->getAttachments() as $attachment) {
            $letter->addAttachment($attachment);
        }

        // Create and send letter
        try {
            $letter
                ->create()
                ->send();

            return new TransportStatus($message, 1);
        } catch (ClientException $e) {
            $errorInformation = \GuzzleHttp\json_decode($e->getResponse()->getBody());
            /** @noinspection PhpUndefinedMethodInspection */
            $errorDescription = sprintf(
                'The E-POST writer letter could not be sent due following error(s): <ol><li>%s</li></ol>',
                implode(
                    '</li> <li>',
                    array_map(
                        function ($error) {
                            return sprintf(
                                'Error (<em>%s</em>): <strong>%s</strong>',
                                $error->type,
                                $error->description
                            );
                        },
                        $errorInformation->error_details
                    )
                )
            );

            \System::log(strip_tags($errorDescription), __METHOD__, TL_ERROR);

            return new TransportStatus($message, 0, $message->getRecipients(), $e);
        }

    }


    /**
     * Flush transport.
     *
     * @return void
     */
    public function flush()
    {
        $this->user->__destruct();
    }
}
