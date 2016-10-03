<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Dca;


use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\ContaoBackendViewTemplate;
use EPost\Helper\HybridRecipientFactory;
use EPost\Model\Letter;
use EPost\Model\LetterContent;
use EPost\Model\Template;


class Writer
{

    /**
     * Add the type of content element
     *
     * @param array $row The data
     *
     * @return string
     */
    public function parseLetterContentElement($row)
    {
        $key = $row['invisible'] ? 'unpublished' : 'published';
        $type = $GLOBALS['TL_LANG']['CTE'][$row['type']][0] ?: '&nbsp;';
        $class = 'limit_height';

        // Add the headline level (see #5858)
        if ('headline' === $row['type']) {
            if (is_array(($headline = deserialize($row['headline'])))) {
                $type .= ' ('.$headline['unit'].')';
            }
        }

        // Limit the element's height
        if (!\Config::get('doNotCollapse')) {
            $class .= ' h64';
        }

        $model = new LetterContent();
        $model->setRow($row);

        $class = trim($class);
        $content = \StringUtil::insertTagToSrc(\Controller::getContentElement($model));

        return <<<HTML

<div class="cte_type $key">$type</div>
<div class="$class">
$content
</div>
HTML;
    }


    /**
     * Handle click on "send letter"
     *
     * @param \DataContainer $dc
     *
     * @return string
     */
    public function sendLetter(\DataContainer $dc)
    {
        if ('send_letter' !== \Input::get('key')) {
            return '';
        }

        /** @var Letter $letter */
        $letter = Letter::findByPk($dc->id);

        // Process submit
        if (Letter::getTable() === \Input::post('FORM_SUBMIT') && 'auto' !== \Input::post('SUBMIT_TYPE')) {
            \Controller::redirect('system/modules/epost-writer/assets/web/send.php?id='.$letter->id);
        }

        $pdfPath = 'system/modules/epost-writer/assets/letters/'.uniqid().'.pdf';
        $recipientMembers = $letter->getRecipientMemberIds();

        if (empty($recipientMembers)) {
            return '';
        }

        /** @var Template $template */
        $template = $letter->getRelated('template');

        $previewNr = \Input::post('preview_nr') ? \Input::post('preview_nr') - 1 : 0;
        $previewNr = array_key_exists($previewNr, $recipientMembers) ? $previewNr : 0;
        $previewMemberId = $recipientMembers[$previewNr];

        $previewSwitcherInput = new \FormTextField(
            [
                'name'     => 'preview_nr',
                'rgxp'     => 'natural',
                'onchange' => 'Backend.autoSubmit(this.form)',
                'value'    => $previewNr + 1,
            ]
        );

        $switcher = sprintf('Datensatz Nr. %s von %u', $previewSwitcherInput->generate(), count($recipientMembers));

        $pdf = $template->createLetterAsPdf(
            $dc->id,
            HybridRecipientFactory::createFromModel(\MemberModel::findByPk($previewMemberId))
        );
        $pdf->Output(TL_ROOT.'/'.$pdfPath, 'F');

        // Embed pdf on localhost system
        if (in_array(\Environment::get('ip'), ['127.0.0.1', '::1',])) {
            $pdfPreview = <<<HTML
<embed src="$pdfPath" width="100%" height="500px" type="application/pdf">
HTML;
        } // Embed pdf on online system
        else {

            $pdfPreview = sprintf(
                <<<'HTML'
    <iframe src="http://docs.google.com/gview?url=%s/%s&embedded=true" style="width:100%%; height:500px;" frameborder="0"></iframe>
HTML
                ,
                \Environment::get('url').\Environment::get('path'),
                $pdfPath
            );
        }

        $backendViewTemplate = new ContaoBackendViewTemplate('dcbe_general_edit');
        $backendViewTemplate->setData(
            [
                'fieldsets'   => [['class' => 'send_letter_preview', 'palette' => $switcher.$pdfPreview]],
                'subHeadline' => sprintf('Den Brief ID %u versenden…', $dc->id),
                'table'       => Letter::getTable(),
                'enctype'     => 'multipart/form-data',
//				'error'       => $this->errors,
                'editButtons' => [
                    'save' => sprintf(
                        '<input type="submit" name="send" id="send" class="tl_submit" value="%s">',
                        'Senden'
                    ),
                ],
//				'noReload'    => (bool) $this->errors,
//				'breadcrumb'  => 'breadcrumb'
            ]
        );

        return $backendViewTemplate->parse();
    }
}
