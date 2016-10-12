<?php

/**
 * This file is part of contao-community-alliance/dc-general.
 *
 * (c) 2013-2015 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/dc-general
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  2013-2015 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/dc-general/blob/master/LICENSE LGPL-3.0
 * @filesource
 */
use Avisota\Queue\SimpleDatabaseQueue;
use EPost\AvisotaBridge\Transport\EPostTransport;
use EPost\Model\User;
use EPost\Model\WriterConfig;


/** @var Pimple $container */

$container['avisota.queue.epost-writer'] = $container->share(
    function ($container) {
        return new SimpleDatabaseQueue($container['doctrine.connection.default'], 'queue_epost_writer', true);
    }
);

$container['avisota.transport.epost-writer'] = $container->share(
    function ($container) {
        return new EPostTransport(User::findByPk(WriterConfig::getInstance()->transport_user));
    }
);
