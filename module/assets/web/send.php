<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

use EPost\AvisotaBridge\RecipientSource\EPostWriter as WriterRecipientSource;
use EPost\AvisotaBridge\Template\EPostTemplate;
use EPost\Model\Letter;


$dir = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);

while ($dir && $dir != '.' && $dir != '/' && !is_file($dir.'/system/initialize.php')) {
    $dir = dirname($dir);

}

if (!is_file($dir.'/system/initialize.php')) {
    header("HTTP/1.0 500 Internal Server Error");
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>500 Internal Server Error</h1>';
    echo '<p>Could not find initialize.php!</p>';
    exit(1);
}

define('TL_MODE', 'BE');
/** @noinspection PhpIncludeInspection */
require($dir.'/system/initialize.php');

BackendUser::getInstance();

/**
 * Class send_immediate
 */
class send extends \Backend
{

    /**
     * AbstractWebRunner constructor.
     */
    public function __construct()
    {
        // preserve object initialisation order
        \BackendUser::getInstance();
        \Database::getInstance();

        parent::__construct();
    }


    /**
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function run()
    {
        $messageId = \Input::get('id');
        $message = Letter::findByPk($messageId);

        if (null === $message) {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        $user = \BackendUser::getInstance();
        $user->authenticate();

        $this->execute($message, $user);
    }


    /**
     * @param Letter      $message
     * @param BackendUser $user
     *
     * @return mixed|void
     */
    protected function execute(Letter $message, \BackendUser $user)
    {
        global $container;

        \Controller::loadLanguageFile('epost_outbox');

        $queue = $container['avisota.queue.epost-writer'];

        /** @var \Avisota\RecipientSource\RecipientSourceInterface $recipientSource */
        $recipientSource = new WriterRecipientSource($message->getRecipientMemberIds());

        $messageTemplate = new EPostTemplate();
        $additionalData = [];

//        $additionalData += [
//            'attachments' => $message->getAttachments()
//        ];


        $additionalData += [
            'letter' => $message,
        ];

        $turn = \Input::get('turn');

        if (!$turn) {
            $turn = 0;
        }

        $loop = \Input::get('loop');

        if (!$loop) {
            $loop = uniqid();
        }

        $queueHelper = new \Avisota\Queue\QueueHelper();
        $queueHelper->setEventDispatcher($this->getEventDispatcher());
        $queueHelper->setQueue($queue);
        $queueHelper->setRecipientSource($recipientSource);
        $queueHelper->setMessageTemplate($messageTemplate);
        $queueHelper->setNewsletterData($additionalData);

        $count = $queueHelper->enqueue(30, $turn * 30);

        if ($count || ($turn * 30 + 30) < $recipientSource->countRecipients()) {

            $_SESSION['TL_CONFIRM'][] = sprintf(
                $GLOBALS['TL_LANG']['epost_outbox']['messages_enqueued'],
                $count,
                $turn + 1
            );

            $parameters = [
                'id'   => $message->id,
                'turn' => $turn + 1,
                'loop' => $loop,
            ];
            $url = sprintf(
                '%ssystem/modules/epost-writer/assets/web/send.php?%s',
                \Environment::get('base'),
                http_build_query($parameters)
            );

        } else {
            $parameters = [
                'do'     => 'epost_outbox',
                'action' => 'execute',
            ];
            $url = sprintf(
                '%scontao/main.php?%s',
                \Environment::get('base'),
                http_build_query($parameters)
            );

//            $message->setSendOn(new \DateTime());
//            $entityManager->persist($message);
//            $entityManager->flush();
        }

        echo '<html><head><meta http-equiv="refresh" content="0; URL='.$url.'"></head><body>Still generating...</body></html>';
        exit;
    }


    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $GLOBALS['container']['event-dispatcher'];
    }
}

$send_immediate = new send();
$send_immediate->run();
