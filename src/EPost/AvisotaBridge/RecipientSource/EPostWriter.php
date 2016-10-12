<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\AvisotaBridge\RecipientSource;


use Avisota\Recipient\RecipientInterface;
use Avisota\RecipientSource\RecipientSourceInterface;
use EPost\AvisotaBridge\Recipient\EPostDelegate;
use EPost\Helper\HybridRecipientFactory;


/**
 * Class EPostWriter
 * @package EPost\AvisotaBridge\RecipientSource
 */
class EPostWriter implements RecipientSourceInterface
{

    /**
     * Member collection
     *
     * @var int[]
     */
    protected $memberIds = [];


    /**
     * Member collection
     *
     * @var EPostDelegate[]
     */
    protected $set = [];


    public function __construct(array $memberIds)
    {
        $this->memberIds = $memberIds;
    }


    /**
     * Count the recipients.
     *
     * @return int
     */
    public function countRecipients()
    {
        return count($this->getRecipients());
    }


    /**
     * Get all recipients.
     *
     * @param int $limit  Limit result to given count.
     * @param int $offset Skip certain count of recipients.
     *
     * @return RecipientInterface[]
     */
    public function getRecipients($limit = null, $offset = null)
    {
        if (empty($this->set)) {
            foreach ($this->memberIds as $memberId) {
                $model = \MemberModel::findByPk($memberId);
                $recipient = HybridRecipientFactory::createFromModel($model);

                $this->set[] = EPostDelegate::createForOrigin($recipient);
            }
        }

        $set = $this->set;

        if ($offset > 0) {
            $set = array_slice($set, $offset);
        }
        if ($limit > 0 && $limit < count($set)) {
            $set = array_slice($set, 0, $limit);
        }

        return $set;
    }
}
