<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 * Copyright (c) 2015-2016 Richard Henkenjohann
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */

namespace EPost\Model;


use Contao\Model;
use EPost\Api\Metadata\Envelope\AbstractRecipient;
use EPost\Helper\HybridRecipientFactory;
use Haste\Haste;


/**
 * @property string $documentTpl
 * @property mixed  $margin
 * @property string $color
 * @property string $registered
 */
class Template extends Model
{

    /**
     * @var string
     */
    protected static $strTable = 'tl_epost_template';


    /**
     * Create the letter and return the pdf instance
     *
     * @param int|Letter        $letter
     * @param AbstractRecipient $recipient
     *
     * @return \TCPDF
     * @throws \Exception
     */
    public function createLetterAsPdf($letter, AbstractRecipient $recipient)
    {
        if (!is_object($letter)) {
            $letter = Letter::findByPk($letter);
        }

        $tokens = $this->createTokens($letter, $recipient);

        // TCPDF configuration
        $l = [];
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_charset'] = $GLOBALS['TL_CONFIG']['characterSet'];
        $l['a_meta_language'] = substr($GLOBALS['TL_LANGUAGE'], 0, 2);
        $l['w_page'] = 'page';

        // Include TCPDF config
        require_once TL_ROOT.'/system/config/tcpdf.php';

        // Create new PDF document
//        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
        /** @var \FPDI|\TCPDF $pdf */
        $pdf = new \FPDI(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle(\StringUtil::parseSimpleTokens('coverLetter', $tokens));

        // Prevent font subsetting (huge speed improvement)
        $pdf->setFontSubsetting(false);

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $margins = deserialize($this->margin);

        // Set margins
        $pdf->SetMargins(
            $margins['left'] ?: PDF_MARGIN_LEFT,
            $margins['top'] ?: PDF_MARGIN_TOP,
            $margins['right'] ?: PDF_MARGIN_RIGHT
        );

        // Set auto page breaks
        $pdf->SetAutoPageBreak(true, $margins['bottom'] ?: PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Set some language-dependent strings
        $pdf->setLanguageArray($l);

        // Initialize document and add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);

        // Write the HTML content
        $pdf->writeHTML($this->generateTemplate($letter, $tokens, $recipient), true, 0, true, 0);

        // Import static attachments
        foreach (deserialize($letter->attachments, true) as $attachment) {
            $count = $pdf->setSourceFile(TL_ROOT.'/'.\FilesModel::findByPk($attachment)->path);

            for ($i = 1; $i <= $count; $i++) {
                $pdf->AddPage();
                $pdf->useTemplate($pdf->importPage($i));
            }
        }

        $pdf->lastPage();

        return $pdf;
    }


    protected function createTokens(Letter $letter, AbstractRecipient $recipient)
    {
        $tokens = [];

        $sender = HybridRecipientFactory::createFromModel(\MemberModel::findById(54));

        foreach ($recipient::getConfigurableFields() as $field) {
            $tokens['recipient_'.$field] = $recipient->$field;
        }

        foreach ($sender::getConfigurableFields() as $field) {
            $tokens['sender_'.$field] = $sender->$field;
        }

        return $tokens;
    }


    /**
     * Parse the template and return it as string
     *
     * @param Letter $letter
     * @param array  $tokens
     *
     * @return string
     * @throws \Exception
     */
    protected function generateTemplate(Letter $letter, array $tokens, AbstractRecipient $recipient)
    {
        $template = new \FrontendTemplate($this->documentTpl);

        // Add letter content to template
        $this->addContentElementsToTemplate($letter, $template);
        $this->addRecipientToTemplate($recipient, $template);
        $this->addSenderToTemplate($tokens, $template);

        // Generate template and fix PDF issues, see Contao's ModuleArticle
        $buffer = Haste::getInstance()->call(
            'replaceInsertTags',
            array(\StringUtil::parseSimpleTokens($template->parse(), $tokens), false)
        );
        $buffer = html_entity_decode($buffer, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);

        // Remove form elements and JavaScript links
        $search = array
        (
            '@<form.*</form>@Us',
            '@<a [^>]*href="[^"]*javascript:[^>]+>.*</a>@Us',
        );

        $buffer = preg_replace($search, '', $buffer);

        // URL decode image paths (see contao/core#6411)
        // Make image paths absolute
        $buffer = preg_replace_callback(
            '@(src=")([^"]+)(")@',
            function ($args) {
                if (preg_match('@^(http://|https://)@', $args[2])) {
                    return $args[2];
                }

                return $args[1].TL_ROOT.'/'.rawurldecode($args[2]).$args[3];
            },
            $buffer
        );

        // Handle line breaks in preformatted text
        $buffer = preg_replace_callback('@(<pre.*</pre>)@Us', 'nl2br_callback', $buffer);

        // Default PDF export using TCPDF
        $search = array
        (
            '@<span style="text-decoration: ?underline;?">(.*)</span>@Us',
            '@(<img[^>]+>)@',
            '@(<div[^>]+block[^>]+>)@',
            '@[\n\r\t]+@',
            '@<br( /)?><div class="mod_article@',
            '@href="([^"]+)(pdf=[0-9]*(&|&amp;)?)([^"]*)"@',
        );

        $replace = array
        (
            '<u>$1</u>',
            '<br>$1',
            '<br>$1',
            ' ',
            '<div class="mod_article',
            'href="$1$4"',
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }


    /**
     * Parse the letter's content elements and add them to the template
     *
     * @param Letter            $letter
     * @param \FrontendTemplate $template
     */
    protected function addContentElementsToTemplate(Letter $letter, \FrontendTemplate &$template)
    {
        $content = LetterContent::findVisibleByPid($letter->id);
        $elements = [];

        if (null !== $content) {
            $count = 0;
            $last = $content->count() - 1;

            while ($content->next()) {
                $css = [];

                /** @var LetterContent $row */
                $row = $content->current();

                // Add the "first" and "last" classes (see #2583)
                if ($count == 0 || $count == $last) {
                    if ($count == 0) {
                        $css[] = 'first';
                    }

                    if ($count == $last) {
                        $css[] = 'last';
                    }
                }

                $row->classes = $css;
                $elements[] = \Controller::getContentElement($row);
                ++$count;
            }
        }

        $template->content = implode(PHP_EOL, $elements);
    }


    protected function addSenderToTemplate(array $tokens, \FrontendTemplate &$template)
    {
        $template->sender = $this->sender;
        $template->senderParsed = nl2br(\StringUtil::parseSimpleTokens($this->parseSender, $tokens));
    }


    protected function addRecipientToTemplate(AbstractRecipient $recipient, \FrontendTemplate &$template)
    {
        $addressTemplate = new \FrontendTemplate('epost_addressblock_recipient');
        $addressTemplate->data = $recipient;

        $template->recipient = $recipient;
        $template->recipientParsed = $addressTemplate->parse();
    }
}
