<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Backend;


use Avisota\Queue\QueueInterface;
use EPost\Model\WriterConfig;


class Outbox extends \BackendModule
{

    /**
     * @var string
     */
    protected $strTemplate = 'be_epost_outbox';


    /**
     * Compile the current element
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function compile()
    {
        \System::loadLanguageFile('epost_outbox');

        /** @noinspection PhpUndefinedMethodInspection */
        $this->Template->messages = $this->getMessages();

        $this->executeQueue();
    }


    protected function executeQueue()
    {
        global $container;

        /** @var QueueInterface $queue */
        $queue = $container['avisota.queue.epost-writer'];
        $this->Template->queue = $queue;

        $this->Template->outbox_introduction = (0 === $queue->length())
            ? 'Es befinden sich keine Briefe im Postausgang'
            : sprintf(
                'Es befinden sich %s Briefe im Postausgang. <a href="%s"> Klicken Sie hier, um die Warteschlange abzuarbeiten.</a>',
                $queue->length(),
                \Controller::addToUrl('action=execute')
            );

        if ('execute' === \Input::get('action')) {
            $this->Template->execute = true;
            $this->Template->queue_maxSendTime = json_encode(WriterConfig::getInstance()->queue_max_send_time);
            $this->Template->queue_cyclePause = json_encode(WriterConfig::getInstance()->queue_cycle_pause);

            $GLOBALS['TL_CSS'][] = 'assets/epost/writer/css/be_outbox.css';
            $GLOBALS['TL_JAVASCRIPT'][] = 'assets/epost/writer/js/Number.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'assets/epost/writer/js/be_outbox.js';
        }
    }
}