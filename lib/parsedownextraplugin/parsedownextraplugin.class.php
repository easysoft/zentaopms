<?php
#
#
# Parsedown Extra Plugin
# https://github.com/taufik-nurrohman/parsedown-extra-plugin
#
# (c) Emanuil Rusev
# http://erusev.com
#
# (c) Taufik Nurrohman
# https://mecha-cms.com
#
# For the full license information, view the LICENSE file that was distributed
# with this source code.
#
#

require 'parsedown.php';
require 'parsedownextra.php';

class parsedownextraplugin extends parsedownextra {

    const version = '1.3.6';

    public $abbreviationData = array();

    public $blockCodeAttributes = array();

    public $blockCodeClassFormat = 'language-%s';

    public $blockCodeHtml = null;

    public $blockQuoteAttributes = array();

    public $blockQuoteText = null;

    public $codeAttributes = array();

    public $codeAttributesOnParent = false;

    public $codeHtml = null;

    public $figureAttributes = array();

    public $figuresEnabled = false;

    public $footnoteAttributes = array();

    public $footnoteBackLinkAttributes = array();

    public $footnoteBackLinkHtml = null;

    public $footnoteBackReferenceAttributes = array();

    public $footnoteLinkAttributes = array();

    public $footnoteLinkHtml = null;

    public $footnoteReferenceAttributes = array();

    public $headerAttributes = array();

    public $headerText = null;

    public $imageAttributes = array();

    public $imageAttributesOnParent = false;

    public $linkAttributes = array();

    public $referenceData = array();

    public $tableAttributes = array();

    public $tableColumnAttributes = array();

    public $voidElementSuffix = ' />';

    protected $regexAttribute = '(?:[#.][-\w:\\\]+[ ]*|[-\w:\\\]+(?:=(?:["\'][^\n]*?["\']|[^\s]+)?)?[ ]*)';

    # Method aliases for every configuration property
    public function __call($key, array $arguments = array()) {
        $property = lcfirst(substr($key, 3));
        if (strpos($key, 'set') === 0 && property_exists($this, $property)) {
            $this->{$property} = $arguments[0];
            return $this;
        }
        throw new Exception('Method ' . $key . ' does not exists.');
    }

    public function __construct() {
        if (version_compare(parent::version, '0.8.0-beta-1') < 0) {
            throw new Exception('ParsedownExtraPlugin requires a later version of Parsedown');
        }
        $this->BlockTypes['!'][] = 'Image';
        parent::__construct();
    }

    protected function blockAbbreviation($Line) {
        // Allow empty abbreviations
        if (preg_match('/^\*\[(.+?)\]:[ ]*$/', $Line['text'], $matches)) {
            $this->DefinitionData['Abbreviation'][$matches[1]] = null;
            return array('hidden' => true);
        }
        return parent::blockAbbreviation($Line);
    }

    protected function blockCodeComplete($Block) {
        $this->doSetAttributes($Block['element']['element'], $this->blockCodeAttributes);
        $this->doSetContent($Block['element']['element'], $this->blockCodeHtml, true);
        // Put code attributes on parent element
        if ($this->codeAttributesOnParent) {
            if ($this->codeAttributesOnParent === true) {
                // $this->codeAttributesOnParent = array_keys($Block['element']['element']['attributes']);
                $this->codeAttributesOnParent = array('class', 'id');
            }
            foreach ((array) $this->codeAttributesOnParent as $Name) {
                if (isset($Block['element']['element']['attributes'][$Name])) {
                    $Block['element']['attributes'][$Name] = $Block['element']['element']['attributes'][$Name];
                    unset($Block['element']['element']['attributes'][$Name]);
                }
            }
        }
        $Block['element']['element']['rawHtml'] = $Block['element']['element']['text'];
        $Block['element']['element']['allowRawHtmlInSafeMode'] = true;
        unset($Block['element']['element']['text']);
        return $Block;
    }

    protected function blockFencedCode($Line) {
        // Re-enable the multiple class name feature
        $Line['text'] = strtr(trim($Line['text']), array(
            ' ' => "\x1A",
            '.' => "\x1A."
        ));
        // Enable custom attribute syntax on code block
        $Attributes = array();
        if (strpos($Line['text'], '{') !== false && substr($Line['text'], -1) === '}') {
            $Parts = explode('{', $Line['text'], 2);
            $Attributes = $this->parseAttributeData(strtr(substr($Parts[1], 0, -1), "\x1A", ' '));
            $Line['text'] = trim($Parts[0]);
        }
        if (!$Block = parent::blockFencedCode($Line)) {
            return;
        }
        if ($Attributes) {
            $Block['element']['element']['attributes'] = $Attributes;
        } else if (isset($Block['element']['element']['attributes']['class'])) {
            $Classes = explode("\x1A", strtr($Block['element']['element']['attributes']['class'], ' ', "\x1A"));
            // `~~~ php` → `<pre><code class="language-php">`
            // `~~~ php html` → `<pre><code class="language-php language-html">`
            // `~~~ .php` → `<pre><code class="php">`
            // `~~~ .php.html` → `<pre><code class="php html">`
            // `~~~ .php html` → `<pre><code class="php language-html">`
            // `~~~ {.php #foo}` → `<pre><code id="foo" class="php">`
            $Results = [];
            foreach ($Classes as $Class) {
                if ($Class === "" || $Class === strtr($this->blockCodeClassFormat, array('%s' => ""))) {
                    continue;
                }
                if ($Class[0] === '.') {
                    $Results[] = substr($Class, 1);
                } else if (preg_match('/^' . strtr(preg_quote($this->blockCodeClassFormat), array('%s' => '\S+')) . '$/', $Class)) {
                    $Results[] = $Class; // Do nothing!
                } else {
                    $Results[] = sprintf($this->blockCodeClassFormat, $Class);
                }
            }
            if ($Results = array_unique($Results)) {
                $Block['element']['element']['attributes']['class'] = implode(' ', $Results);
            } else {
                unset($Block['element']['element']['attributes']['class']);
            }
        }
        return $Block;
    }

    protected function blockFencedCodeComplete($Block) {
        return $this->blockCodeComplete($Block);
    }

    protected function blockHeader($Line) {
        if (!$Block = parent::blockHeader($Line)) {
            return;
        }
        $Level = strspn($Line['text'], '#');
        $this->doSetAttributes($Block['element'], $this->headerAttributes, array($Level));
        $this->doSetContent($Block['element'], $this->headerText, false, 'argument', array($Level));
        return $Block;
    }

    protected function blockImage($Line) {
        if (!$this->figuresEnabled) {
            return;
        }
        // Match exactly an image syntax in a paragraph (with optional custom attributes, and optional hard break marker)
        if (preg_match('/^\!\[[^\n]*?\](\[[^\n]*?\]|\([^\n]*?\))(\s*\{' . $this->regexAttribute . '+?\})?([ ]{2})?$/', $Line['text'])) {
            $Block = array(
                'description' => "",
                'element' => array(
                    'name' => 'figure',
                    'attributes' => array(),
                    'elements' => array(
                        $this->inlineImage($Line)
                    )
                )
            );
            $this->doSetAttributes($Block['element'], $this->figureAttributes);
            return $Block;
        }
        return;
    }

    protected function blockImageComplete($Block) {
        if (!empty($Block['description'])) {
            $Description = $Block['description'];
            $Block['element']['elements'][] = array(
                'name' => 'figcaption',
                'rawHtml' => $this->{strpos($Description, "\n\n") === false ? 'line' : 'text'}(trim($Description, "\n"))
            );
            // unset($Block['description']);
        }
        if ($this->imageAttributesOnParent) {
            $Inline = $Block['element']['elements'][0];
            if ($this->imageAttributesOnParent === true) {
                $this->imageAttributesOnParent = array_keys($Inline['element']['attributes']);
            }
            foreach ((array) $this->imageAttributesOnParent as $Name) {
                if (isset($Inline['element']['attributes'][$Name])) {
                    // Merge class names
                    if (
                        $Name === 'class' &&
                        isset($Block['element']['attributes'][$Name]) &&
                        isset($Inline['element']['attributes'][$Name])
                    ) {
                        $Classes = array_merge(
                            explode(' ', $Block['element']['attributes'][$Name]),
                            explode(' ', $Inline['element']['attributes'][$Name])
                        );
                        sort($Classes);
                        $Block['element']['attributes']['class'] = implode(' ', array_unique(array_filter($Classes)));
                        unset($Block['element']['elements'][0]['element']['attributes'][$Name]);
                        continue;
                    }
                    $Block['element']['attributes'][$Name] = $Inline['element']['attributes'][$Name];
                    unset($Block['element']['elements'][0]['element']['attributes'][$Name]);
                }
            }
        }
        return $Block;
    }

    protected function blockImageContinue($Line, array $Block) {
        if (isset($Block['complete'])) {
            return;
        }
        if (isset($Block['interrupted'])) {
            $Block['description'] .= "\n";
            unset($Block['interrupted']);
        }
        if ($Line['indent'] === 0) {
            $Block['complete'] = true;
            return;
        }
        if ($Line['indent'] > 0 && $Line['indent'] < 4) {
            $Block['description'] .= "\n" . $Line['text'];
            return $Block;
        }
        return;
    }

    protected function blockQuoteComplete($Block) {
        $this->doSetAttributes($Block['element'], $this->blockQuoteAttributes);
        $this->doSetContent($Block['element'], $this->blockQuoteText, false, 'arguments');
        return $Block;
    }

    protected function blockSetextHeader($Line, array $Block = null) {
        if (!$Block = parent::blockSetextHeader($Line, $Block)) {
            return;
        }
        $Level = $Line['text'][0] === '=' ? 1 : 2;
        $this->doSetAttributes($Block['element'], $this->headerAttributes, array($Level));
        $this->doSetContent($Block['element'], $this->headerText, false, 'argument', array($Level));
        return $Block;
    }

    protected function blockTableContinue($Line, array $Block) {
        if (!$Block = parent::blockTableContinue($Line, $Block)) {
            return;
        }
        $Aligns = $Block['alignments'];
        // `<thead>` or `<tbody>`
        foreach ($Block['element']['elements'] as $Index0 => &$Element0) {
            // `<tr>`
            foreach ($Element0['elements'] as $Index1 => &$Element1) {
                // `<th>` or `<td>`
                foreach ($Element1['elements'] as $Index2 => &$Element2) {
                    $this->doSetAttributes($Element2, $this->tableColumnAttributes, array($Aligns[$Index2], $Index2, $Index1));
                }
            }
        }
        return $Block;
    }

    protected function blockTableComplete($Block) {
        $this->doSetAttributes($Block['element'], $this->tableAttributes);
        return $Block;
    }

    protected function buildFootnoteElement() {
        $DefinitionData = $this->DefinitionData['Footnote'];
        if (!$Footnotes = parent::buildFootnoteElement()) {
            return;
        }
        $DefinitionKey = array_keys($DefinitionData);
        $DefinitionData = array_values($DefinitionData);
        $this->doSetAttributes($Footnotes, $this->footnoteAttributes);
        foreach ($Footnotes['elements'][1]['elements'] as $Index0 => &$Element0) {
            $Name = $DefinitionKey[$Index0];
            $Count = $DefinitionData[$Index0]['count'];
            $Args = array(is_numeric($Name) ? (float) $Name : $Name, $Count);
            $this->doSetAttributes($Element0, $this->footnoteBackReferenceAttributes, $Args);
            foreach ($Element0['elements'] as $Index1 => &$Element1) {
                if (!isset($Element1['elements'])) {
                    continue;
                }
                $Count = 0;
                foreach ($Element1['elements'] as $Index2 => &$Element2) {
                    if (!isset($Element2['name']) || $Element2['name'] !== 'a') {
                        continue;
                    }
                    $Args[1] = ++$Count;
                    $this->doSetAttributes($Element2, $this->footnoteBackLinkAttributes, $Args);
                    $this->doSetContent($Element2, $this->footnoteBackLinkHtml, false, 'rawHtml');
                }
            }
        }
        return $Footnotes;
    }

    protected function doGetAttributes($Element) {
        if (isset($Element['attributes'])) {
            return (array) $Element['attributes'];
        }
        return array();
    }

    protected function doGetContent($Element) {
        if (isset($Element['text'])) {
            return $Element['text'];
        }
        if (isset($Element['rawHtml'])) {
            return $Element['rawHtml'];
        }
        if (isset($Element['handler']['argument'])) {
            return implode("\n", (array) $Element['handler']['argument']);
        }
        return null;
    }

    private function doSetLink($Excerpt, $Function) {
        if (!$Inline = call_user_func('parent::' . $Function, $Excerpt)) {
            return;
        }
        $this->doSetAttributes($Inline['element'], $this->linkAttributes, array($this->isLocal($Inline['element'], 'href')));
        $this->doSetData($this->DefinitionData['Reference'], $this->referenceData);
        return $Inline;
    }

    protected function doSetAttributes(&$Element, $From, $Args = array()) {
        $Attributes = $this->doGetAttributes($Element);
        $Content = $this->doGetContent($Element);
        if (is_callable($From)) {
            $Args = array_merge(array($Content, $Attributes, &$Element), $Args);
            $Element['attributes'] = array_replace($Attributes, (array) call_user_func_array($From, $Args));
        } else {
            $Element['attributes'] = array_replace($Attributes, (array) $From);
        }
    }

    protected function doSetContent(&$Element, $From, $Esc = false, $Mode = 'text', $Args = array()) {
        $Attributes = $this->doGetAttributes($Element);
        $Content = $this->doGetContent($Element);
        if ($Esc) {
            $Content = parent::escape($Content, true);
        }
        if (is_callable($From)) {
            $Args = array_merge(array($Content, $Attributes, &$Element), $Args);
            $Content = call_user_func_array($From, $Args);
        } else if (!empty($From)) {
            $Content = sprintf($From, $Content);
        }
        if ($Mode === 'arguments') {
            $Element['handler']['argument'] = explode("\n", $Content);
        } else if ($Mode === 'argument') {
            $Element['handler']['argument'] = $Content;
        } else {
            $Element[$Mode] = $Content;
        }
    }

    protected function doSetData(&$To, $From) {
        $To = array_replace((array) $To, (array) $From);
    }

    protected function element(array $Element) {
        if (!$Any = parent::element($Element)) {
            return;
        }
        if (substr($Any, -3) === ' />') {
            if (is_callable($this->voidElementSuffix)) {
                $Attributes = $this->doGetAttributes($Element);
                $Content = $this->doGetContent($Element);
                $Suffix = call_user_func_array($this->voidElementSuffix, [$Content, $Attributes, &$Element]);
            } else {
                $Suffix = $this->voidElementSuffix;
            }
            $Any = substr_replace($Any, $Suffix, -3);
        }
        return $Any;
    }

    protected function inlineCode($Excerpt) {
        if (!$Inline = parent::inlineCode($Excerpt)) {
            return;
        }
        $this->doSetAttributes($Inline['element'], $this->codeAttributes);
        $this->doSetContent($Inline['element'], $this->codeHtml, true);
        $Inline['element']['rawHtml'] = $Inline['element']['text'];
        $Inline['element']['allowRawHtmlInSafeMode'] = true;
        unset($Inline['element']['text']);
        return $Inline;
    }

    protected function inlineFootnoteMarker($Excerpt) {
        if (!$Inline = parent::inlineFootnoteMarker($Excerpt)) {
            return;
        }
        $Name = null;
        if (preg_match('/^\[\^(.+?)\]/', $Excerpt['text'], $matches)) {
            $Name = $matches[1];
        }
        $Args = array(is_numeric($Name) ? (float) $Name : $Name, $this->DefinitionData['Footnote'][$Name]['count']);
        $this->doSetAttributes($Inline['element'], $this->footnoteReferenceAttributes, $Args);
        $this->doSetAttributes($Inline['element']['element'], $this->footnoteLinkAttributes, $Args);
        $this->doSetContent($Inline['element']['element'], $this->footnoteLinkHtml, false, 'text', $Args);
        $Inline['element']['element']['rawHtml'] = $Inline['element']['element']['text'];
        $Inline['element']['element']['allowRawHtmlInSafeMode'] = true;
        unset($Inline['element']['element']['text']);
        return $Inline;
    }

    protected function inlineImage($Excerpt) {
        if (!$Inline = parent::inlineImage($Excerpt)) {
            return;
        }
        $this->doSetAttributes($Inline['element'], $this->imageAttributes, array($this->isLocal($Inline['element'], 'src')));
        return $Inline;
    }

    protected function inlineLink($Excerpt) {
        return $this->doSetLink($Excerpt, __FUNCTION__);
    }

    protected function inlineText($Text) {
        $this->doSetData($this->DefinitionData['Abbreviation'], $this->abbreviationData);
        return parent::inlineText($Text);
    }

    protected function inlineUrl($Excerpt) {
        return $this->doSetLink($Excerpt, __FUNCTION__);
    }

    protected function inlineUrlTag($Excerpt) {
        return $this->doSetLink($Excerpt, __FUNCTION__);
    }

    protected function isLocal($Element, $Key) {
        $Link = isset($Element['attributes'][$Key]) ? (string) $Element['attributes'][$Key] : null;
        if (
            // `<a href="">`
            $Link === "" ||
            // `<a href="../foo/bar">`
            // `<a href="/foo/bar">`
            // `<a href="?foo=bar">`
            // `<a href="&foo=bar">`
            // `<a href="#foo">`
            strpos('./?&#', $Link[0]) !== false && strpos($Link, '//') !== 0 ||
            // `<a href="data:text/html,asdf">`
            strpos($Link, 'data:') === 0 ||
            // `<a href="javascript:;">`
            strpos($Link, 'javascript:') === 0 ||
            // `<a href="mailto:as@df">`
            strpos($Link, 'mailto:') === 0
        ) {
            return true;
        }
        if (isset($_SERVER['HTTP_HOST'])) {
            $Host = $_SERVER['HTTP_HOST'];
        } else if (isset($_SERVER['SERVER_NAME'])) {
            $Host = $_SERVER['SERVER_NAME'];
        } else {
            $Host = "";
        }
        // `<a href="//example.com">`
        if (strpos($Link, '//') === 0 && strpos($Link, '//' . $Host) !== 0) {
            return false;
        }
        if (
            // `<a href="https://127.0.0.1">`
            strpos($Link, 'https://' . $Host) === 0 ||
            // `<a href="http://127.0.0.1">`
            strpos($Link, 'http://' . $Host) === 0
        ) {
            return true;
        }
        // `<a href="foo/bar">`
        return strpos($Link, '://') === false;
    }

    protected function parseAttributeData($attributeString) {
        // Allow compact attributes
        $attributeString = strtr($attributeString, array(
            '#' => ' #',
            '.' => ' .'
        ));
        if (strpos($attributeString, '="') !== false || strpos($attributeString, "='") !== false) {
            $attributeString = preg_replace_callback('#([-\w]+=)(["\'])([^\n]*?)\2#', function($matches) {
                $value = strtr($matches[3], array(
                    ' #' => '#',
                    ' .' => '.',
                    ' ' => "\x1A"
                ));
                return $matches[1] . $matches[2] . $value . $matches[2];
            }, $attributeString);
        }
        $Attributes = array();
        foreach (explode(' ', $attributeString) as $v) {
            if (!$v) {
                continue;
            }
            // `{#foo}`
            if ($v[0] === '#' && isset($v[1])) {
                $Attributes['id'] = substr($v, 1);
            // `{.foo}`
            } else if ($v[0] === '.' && isset($v[1])) {
                $Attributes['class'][] = substr($v, 1);
            // ~
            } else if (strpos($v, '=') !== false) {
                $vv = explode('=', $v, 2);
                // `{foo=}`
                if ($vv[1] === "") {
                    if ($vv[0] === 'class') {
                        continue;
                    }
                    $Attributes[$vv[0]] = "";
                // `{foo="bar baz"}`
                // `{foo='bar baz'}`
                } else if ($vv[1][0] === '"' && substr($vv[1], -1) === '"' || $vv[1][0] === "'" && substr($vv[1], -1) === "'") {
                    $values = stripslashes(strtr(substr(substr($vv[1], 1), 0, -1), "\x1A", ' '));
                    if ($vv[0] === 'class' && isset($Attributes[$vv[0]])) {
                        $values = explode(' ', $values);
                        $Attributes[$vv[0]] = array_merge($Attributes[$vv[0]], $values);
                    } else {
                        $Attributes[$vv[0]] = $values;
                    }
                // `{foo=bar}`
                } else {
                    if ($vv[0] === 'class' && isset($Attributes[$vv[0]])) {
                        $Attributes[$vv[0]] = array_merge($Attributes[$vv[0]], [$vv[1]]);
                    } else {
                        $Attributes[$vv[0]] = $vv[1];
                    }
                }
            // `{foo}`
            } else {
                if ($v === 'class' && isset($Attributes[$v])) {
                    continue;
                }
                $Attributes[$v] = $v;
            }
        }
        if (isset($Attributes['class'])) {
            $Attributes['class'] = implode(' ', array_unique((array) $Attributes['class']));
        }
        return $Attributes;
    }

}
