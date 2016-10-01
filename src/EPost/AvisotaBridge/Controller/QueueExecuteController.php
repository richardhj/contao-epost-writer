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

namespace EPost\AvisotaBridge\Controller;

use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\QueueInterface;
use Avisota\Transport\TransportInterface;
use EPost\Helper\Config as EPostConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class QeueueExecuteController
 *
 * @package Avisota\Contao\Core\Controller
 */
class QueueExecuteController extends \Backend
{

    /**
     * Load the database object
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param Request $request
     *
     * @return JsonResponse|mixed
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function run(Request $request)
    {
        $user = \BackendUser::getInstance();
        $user->authenticate();

        try {
            return $this->execute($request, $user);
        } catch (\Exception $exception) {
            $response = new JsonResponse(
                [
                    'error' => sprintf(
                        '%s in %s:%d',
                        $exception->getMessage(),
                        $exception->getFile(),
                        $exception->getLine()
                    ),
                ],
                500
            );
            $response->prepare($request);

            return $response;
        }
    }


    protected function execute(Request $request, \BackendUser $user)
    {
        global $container;

        /** @var QueueInterface $queue */
        $queue = $container['avisota.queue.epost-writer'];

        $config = new ExecutionConfig();

        if (EPostConfig::get(EPostConfig::QUEUE_MAX_SEND_TIME) > 0) {
            $config->setTimeLimit(EPostConfig::get(EPostConfig::QUEUE_MAX_SEND_TIME));
        }
        if (EPostConfig::get(EPostConfig::QUEUE_MAX_SEND_COUNT) > 0) {
            $config->setMessageLimit(EPostConfig::get(EPostConfig::QUEUE_MAX_SEND_COUNT));
        }

        /** @var TransportInterface $transport */
        $transport = $container['avisota.transport.epost-writer'];

        $status = $queue->execute($transport, $config);

        $jsonData = [
            'success' => 0,
            'failed'  => 0,
        ];

        foreach ($status as $stat) {
            $jsonData['success'] += $stat->getSuccessfullySend();
            $jsonData['failed'] += count($stat->getFailedRecipients());
        }

        $response = new JsonResponse($jsonData);
        $response->prepare($request);

        return $response;
    }
}
