<?php
require_once 'Michelf/MarkdownExtra.inc.php';
use Michelf\Markdown;
use Michelf\MarkdownExtra;

class michelf 
{
    /**
     * Convert markdown to html parser.
     * 
     * @param  string $mdCodes 
     * @static
     * @access public
     * @return void
     */
    public static function parse($mdCodes = '')
    {
        if(strlen($mdCodes) == 0) return '';
        $html    = Markdown::defaultTransform($mdCodes);
        $parser  = new MarkdownExtra;

        $parser->fn_id_prefix = "post22-";
        $html    = $parser->transform($mdCodes);

        return "<div class='markdown-print'>$html</div>";
    }
}
