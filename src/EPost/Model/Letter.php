<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 * Copyright (c) 2015-2016 Richard Henkenjohann
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Model;


use Contao\Model;


/**
 * Class Letter
 * @property string $title
 * @property int    $template
 * @property int    $sender
 * @property bool   $send_to_member
 * @property bool   $send_to_member_group
 * @property mixed  $recipients_member
 * @property mixed  $recipients_member_group
 * @package EPost\Model
 */
class Letter extends Model
{

    /**
     * @var string
     */
    protected static $strTable = 'tl_epost_letter';


    public function getRecipientMemberIds()
    {
        $members = [];

        if ($this->send_to_member) {
            $members = array_merge($members, deserialize($this->recipients_member));
        }

        if ($this->send_to_member_group) {
            foreach (deserialize($this->recipients_member_group) as $group) {
                $membersInGroup = \Database::getInstance()
                    ->prepare("SELECT member_id FROM tl_member_to_group WHERE group_id=?")
                    ->execute($group)->fetchEach('member_id');

                $members = array_merge($members, array_values($membersInGroup));
            }

        }

        return array_unique($members);
    }


//    public function send()
//    {
//        /** @var Template $template */
//        $template = $this->getRelated('template');
//
//            /** @var $objQueueManager \NotificationCenter\Queue\QueueManagerInterface */
//            $objQueueManager = $GLOBALS['NOTIFICATION_CENTER']['QUEUE_MANAGER'];
//
//        foreach ($this->getRecipientMemberIds() as $memberId) {
//            $pdfPath = TL_ROOT.'/system/tmp/epost-letter-'.$this->id.'-'.uniqid().'.pdf';
//
//            $pdf = $template->createLetterAsPdf($this->id, $memberId);
//            $pdf->Output($pdfPath, 'F');
//
//
//            $objMessage = new Message();
//            $objMessage->epost_recipient_member = $memberId;
//
//
//            $arrTokens = [];
//            $strLanguage = '';
//
//            // Add to queue
//            $objQueueManager->addMessage($objMessage, $arrTokens, $strLanguage);
//        }
//
//        $email = new \Email();
//        $email->subject = 'test';
//        $email->text = 'test';
//        $email->sendTo('richard-test@henkenjohann.me');
//
////@todo lock letter to prohibit changes
//
//        return true;
//    }

}
