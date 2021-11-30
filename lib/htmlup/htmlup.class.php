<?php
/*
* This file is part of the HTMLUP package.
*
* (c) Jitendra Adhikari <jiten.adhikary@gmail.com>
*     <https://github.com/adhocore>
*
* Licensed under MIT license.
*/

trait HtmlHelper
{
public function escape($input)
{
    return \htmlspecialchars($input);
}

public function h($level, $line)
{
    if (\is_string($level)) {
        $level = \trim($level, '- ') === '' ? 2 : 1;
    }

    if ($level < 7) {
        return "\n<h{$level}>" . \ltrim(\ltrim($line, '# ')) . "</h{$level}>";
    }

    return '';
}

public function hr($prevLine, $line)
{
        if ($prevLine === '' && \preg_match(BlockElementParser::RE_MD_RULE, $line)) {
            return "\n<hr />";
        }
    }

    public function codeStart($lang)
    {
        $lang = isset($lang[1])
            ? ' class="language-' . $lang[1] . '"'
            : '';

        return "\n<pre><code{$lang}>";
    }

    public function codeLine($line, $isBlock, $indentLen = 4)
    {
        $code  = "\n"; // @todo: donot use \n for first line
        $code .= $isBlock ? $line : \substr($line, $indentLen);

        return $code;
    }

    public function tableStart($line, $delim = '|')
    {
        $table = "<table>\n<thead>\n<tr>\n";

        foreach (\explode($delim, \trim($line, $delim)) as $hdr) {
            $table .= '<th>' . \trim($hdr) . "</th>\n";
        }

        $table .= "</tr>\n</thead>\n<tbody>\n";

        return $table;
    }

    public function tableRow($line, $colCount, $delim = '|')
    {
        $row = "<tr>\n";

        foreach (\explode($delim, \trim($line, $delim)) as $i => $col) {
            if ($i > $colCount) {
                break;
            }

            $col  = \trim($col);
            $row .= "<td>{$col}</td>\n";
        }

        $row .= "</tr>\n";

        return $row;
    }
}

abstract class BlockElementParser
{
    use HtmlHelper;

    const RE_MD_QUOTE  = '~^\s*(>+)\s+~';
    const RE_RAW       = '/^<\/?\w.*?\/?>/';
    const RE_MD_SETEXT = '~^\s*(={3,}|-{3,})\s*$~';
    const RE_MD_CODE   = '/^```\s*([\w-]+)?/';
    const RE_MD_RULE   = '~^(_{3,}|\*{3,}|\-{3,})$~';
    const RE_MD_TCOL   = '~(\|\s*\:)?\s*\-{3,}\s*(\:\s*\|)?~';
    const RE_MD_OL     = '/^\d+\. /';

    protected $lines       = [];
    protected $stackList   = [];
    protected $stackBlock  = [];
    protected $stackTable  = [];

    protected $pointer     = -1;
    protected $listLevel   = 0;
    protected $quoteLevel  = 0;
    protected $indent      = 0;
    protected $nextIndent  = 0;
    protected $indentLen   = 4;

    protected $indentStr       = '    ';
    protected $line            = '';
    protected $trimmedLine     = '';
    protected $prevLine        = '';
    protected $trimmedPrevLine = '';
    protected $nextLine        = '';
    protected $trimmedNextLine = '';
    protected $markup          = '';

    protected $inList  = \false;
    protected $inQuote = \false;
    protected $inPara  = \false;
    protected $inHtml  = \false;
    protected $inTable = \false;

    protected function parseBlockElements()
    {
        while (isset($this->lines[++$this->pointer])) {
            $this->init();

            if ($this->flush() || $this->raw()) {
                continue;
            }

            $this->quote();

            if (($block = $this->isBlock()) || $this->inList) {
                $this->markup .= $block ? '' : $this->trimmedLine;

                continue;
            }

            $this->table() || $this->paragraph();
        }
    }

    protected function isBlock()
    {
        return $this->atx() || $this->setext() || $this->code() || $this->rule() || $this->listt();
    }

    protected function atx()
    {
        if (\substr($this->trimmedLine, 0, 1) === '#') {
            $level = \strlen($this->trimmedLine) - \strlen(\ltrim($this->trimmedLine, '#'));
            $head  = $this->h($level, $this->trimmedLine);

            $this->markup .= $head;

            return (bool) $head;
        }
    }

    protected function setext()
    {
        if (\preg_match(static::RE_MD_SETEXT, $this->nextLine)) {
            $this->markup .= $this->h($this->nextLine, $this->trimmedLine);

            $this->pointer++;

            return \true;
        }
    }

    protected function code()
    {
        $isShifted = ($this->indent - $this->nextIndent) >= $this->indentLen;
        $codeBlock = \preg_match(static::RE_MD_CODE, $this->line, $codeMatch);

        if ($codeBlock || (!$this->inList && !$this->inQuote && $isShifted)) {
            $this->markup .= $this->codeStart($codeMatch);

            if (!$codeBlock) {
                $this->markup .= $this->escape(\substr($this->line, $this->indentLen));
            }

            $this->codeInternal($codeBlock);

            $this->pointer++;

            $this->markup .= '</code></pre>';

            return \true;
        }
    }

    private function codeInternal($codeBlock)
    {
        while (isset($this->lines[$this->pointer + 1])) {
            $this->line = $this->escape($this->lines[$this->pointer + 1]);

            if (($codeBlock && \substr(\ltrim($this->line), 0, 3) !== '```')
                || \strpos($this->line, $this->indentStr) === 0
            ) {
                $this->markup .= $this->codeLine($this->line, $codeBlock, $this->indentLen);

                $this->pointer++;

                continue;
            }

            break;
        }
    }

    protected function rule()
    {
        $this->markup .= $hr = $this->hr($this->trimmedPrevLine, $this->trimmedLine);

        return (bool) $hr;
    }

    protected function listt()
    {
        $isUl = \in_array(\substr($this->trimmedLine, 0, 2), ['- ', '* ', '+ ']);

        if ($isUl || \preg_match(static::RE_MD_OL, $this->trimmedLine)) {
            $wrapper = $isUl ? 'ul' : 'ol';

            if (!$this->inList) {
                $this->stackList[] = "</$wrapper>";

                $this->markup .= "\n<$wrapper>\n";
                $this->inList  = \true;

                $this->listLevel++;
            }

            $this->markup .= '<li>' . \ltrim($this->trimmedLine, '+-*0123456789. ');

            $this->listInternal();

            return \true;
        }
    }

    private function listInternal()
    {
        $isUl = \in_array(\substr($this->trimmedNextLine, 0, 2), ['- ', '* ', '+ ']);

        if ($isUl || \preg_match(static::RE_MD_OL, $this->trimmedNextLine)) {
            $wrapper = $isUl ? 'ul' : 'ol';
            if ($this->nextIndent > $this->indent) {
                $this->stackList[] = "</li>\n";
                $this->stackList[] = "</$wrapper>";
                $this->markup .= "\n<$wrapper>\n";

                $this->listLevel++;
            } else {
                $this->markup .= "</li>\n";
            }

            if ($this->nextIndent < $this->indent) {
                $shift = \intval(($this->indent - $this->nextIndent) / $this->indentLen);

                while ($shift--) {
                    $this->markup .= \array_pop($this->stackList);

                    if ($this->listLevel > 2) {
                        $this->markup .= \array_pop($this->stackList);
                    }
                }
            }
        } else {
            $this->markup .= "</li>\n";
        }
    }

    protected function table()
    {
        static $headerCount = 0;

        if (!$this->inTable) {
            $headerCount = \substr_count(\trim($this->trimmedLine, '|'), '|');

            return $this->tableInternal($headerCount);
        }

        $this->markup .= $this->tableRow($this->trimmedLine, $headerCount);

        if (empty($this->trimmedNextLine)
            || !\substr_count(\trim($this->trimmedNextLine, '|'), '|')
        ) {
            $headerCount        = 0;
            $this->inTable      = \false;
            $this->stackTable[] = "</tbody>\n</table>";
        }

        return \true;
    }

    private function tableInternal($headerCount)
    {
        $columnCount = \preg_match_all(static::RE_MD_TCOL, \trim($this->trimmedNextLine, '|'));

        if ($headerCount > 0 && $headerCount <= $columnCount) {
            $this->pointer++;

            $this->inTable = \true;
            $this->markup .= $this->tableStart($this->trimmedLine);

            return \true;
        }
    }
}

class SpanElementParser
{
    use HtmlHelper;

    const RE_URL       = '~<(https?:[\/]{2}[^\s]+?)>~';
    const RE_EMAIL     = '~<(\S+?@\S+?)>~';
    const RE_MD_IMG    = '~!\[(.+?)\]\s*\((.+?)\s*(".+?")?\)~';
    const RE_MD_URL    = '~\[(.+?)\]\s*\((.+?)\s*(".+?")?\)~';
    const RE_MD_FONT   = '!(\*{1,2}|_{1,2}|`|~~)(.+?)\\1!';

    public function parse($markup)
    {
        return $this->spans(
            $this->anchors(
                $this->links($markup)
            )
        );
    }

    protected function links($markup)
    {
        $markup = $this->emails($markup);

        return \preg_replace(
            static::RE_URL,
            '<a href="$1">$1</a>',
            $markup
        );
    }

    protected function emails($markup)
    {
        return \preg_replace(
            static::RE_EMAIL,
            '<a href="mailto:$1">$1</a>',
            $markup
        );
    }

    protected function anchors($markup)
    {
        $markup = $this->images($markup);

        return \preg_replace_callback(static::RE_MD_URL, function ($a) {
            $title = isset($a[3]) ? " title={$a[3]} " : '';

            return "<a href=\"{$a[2]}\"{$title}>{$a[1]}</a>";
        }, $markup);
    }

    protected function images($markup)
    {
        return \preg_replace_callback(static::RE_MD_IMG, function ($img) {
            $title = isset($img[3]) ? " title={$img[3]} " : '';
            $alt   = $img[1] ? " alt=\"{$img[1]}\" " : '';

            return "<img src=\"{$img[2]}\"{$title}{$alt}/>";
        }, $markup);
    }

    protected function spans($markup)
    {
        // em/code/strong/del
        return \preg_replace_callback(static::RE_MD_FONT, function ($em) {
            switch (\substr($em[1], 0, 2)) {
                case '**':
                case '__':
                    $tag = 'strong';
                    break;

                case '~~':
                    $tag = 'del';
                    break;

                case $em[1] === '*':
                case $em[1] === '_':
                    $tag = 'em';
                    break;

                default:
                    $tag = 'code';
                    $em[2] = $this->escape($em[2]);
            }

            return "<$tag>{$em[2]}</$tag>";
        }, $markup);
    }
}

/**
 * HtmlUp - A **lightweight** && **fast** `markdown` to HTML Parser.
 *
 * Supports most of the markdown specs except deep nested elements.
 * Check readme.md for the details of its features && limitations.
 *
 * @author    adhocore | Jitendra Adhikari <jiten.adhikary@gmail.com>
 * @copyright (c) 2014 Jitendra Adhikari
 */
class htmlup extends BlockElementParser
{
    /**
     * Constructor.
     *
     * @param string $markdown
     * @param int    $indentWidth
     */
    public function __construct($markdown = \null, $indentWidth = 4)
    {
        $this->scan($markdown, $indentWidth);
    }

    protected function scan($markdown, $indentWidth = 4)
    {
        if ('' === \trim($markdown)) {
            return;
        }

        $this->indentLen = $indentWidth == 2 ? 2 : 4;
        $this->indentStr = $indentWidth == 2 ? '  ' : '    ';

        // Normalize whitespaces
        $markdown = \str_replace("\t", $this->indentStr, $markdown);
        $markdown = \str_replace(["\r\n", "\r"], "\n", $markdown);

        $this->lines = \array_merge([''], \explode("\n", $markdown), ['']);
    }

    public function __toString()
    {
        return $this->parse();
    }

    /**
     * Parse markdown.
     *
     * @param string $markdown
     * @param int    $indentWidth
     *
     * @return string
     */
    public function parse($markdown = \null, $indentWidth = 4)
    {
        if (\null !== $markdown) {
            $this->reset(\true);

            $this->scan($markdown, $indentWidth);
        }

        if (empty($this->lines)) {
            return '';
        }

        $this->parseBlockElements();

        return (new SpanElementParser)->parse($this->markup);
    }

    protected function init()
    {
        list($this->prevLine, $this->trimmedPrevLine) = [$this->line, $this->trimmedLine];

        $this->line        = $this->lines[$this->pointer];
        $this->trimmedLine = \trim($this->line);

        $this->indent   = \strlen($this->line) - \strlen(\ltrim($this->line));
        $this->nextLine = isset($this->lines[$this->pointer + 1])
            ? $this->lines[$this->pointer + 1]
            : '';
        $this->trimmedNextLine = \trim($this->nextLine);
        $this->nextIndent      = \strlen($this->nextLine) - \strlen(\ltrim($this->nextLine));
    }

    protected function reset($all = \false)
    {
        $except = $all ? [] : \array_flip(['lines', 'pointer', 'markup', 'indentStr', 'indentLen']);

        // Reset all current values.
        foreach (\get_class_vars(__CLASS__) as $prop => $value) {
            isset($except[$prop]) || $this->{$prop} = $value;
        }
    }

    protected function flush()
    {
        if ('' !== $this->trimmedLine) {
            return \false;
        }

        while (!empty($this->stackList)) {
            $this->markup .= \array_pop($this->stackList);
        }

        while (!empty($this->stackBlock)) {
            $this->markup .= \array_pop($this->stackBlock);
        }

        while (!empty($this->stackTable)) {
            $this->markup .= \array_pop($this->stackTable);
        }

        $this->markup .= "\n";

        $this->reset(\false);

        return \true;
    }

    protected function raw()
    {
        if ($this->inHtml || \preg_match(static::RE_RAW, $this->trimmedLine)) {
            $this->markup .= "\n$this->line";
            if (!$this->inHtml && empty($this->lines[$this->pointer - 1])) {
                $this->inHtml = \true;
            }

            return \true;
        }
    }

    protected function quote()
    {
        if (\preg_match(static::RE_MD_QUOTE, $this->line, $quoteMatch)) {
            $this->line        = \substr($this->line, \strlen($quoteMatch[0]));
            $this->trimmedLine = \trim($this->line);

            if (!$this->inQuote || $this->quoteLevel < \strlen($quoteMatch[1])) {
                $this->markup .= "\n<blockquote>";

                $this->stackBlock[] = "\n</blockquote>";

                $this->quoteLevel++;
            }

            return $this->inQuote = \true;
        }
    }

    protected function paragraph()
    {
        $this->markup .= $this->inPara ? "\n<br />" : "\n<p>";
        $this->markup .= $this->trimmedLine;

        if (empty($this->trimmedNextLine)) {
            $this->markup .= '</p>';
            $this->inPara = \false;
        } else {
            $this->inPara = \true;
        }
    }
}
