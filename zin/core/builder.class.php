<?php
require_once 'props.class.php';

/**
 * HTML builder
 */
class builder
{
    public $tag = '';

    public $children = array();

    public $propsStr = '';

    public $prefix = array();

    public $suffix = array();

    public $jsCode = array();

    public $cssCode = array();

    public $jsImports = array();

    public $cssImports = array();

    public $jsVars = array();

    public $selfClosing;

    public $inTag = false;

    public function __construct($tag = '')
    {
        $this->tag          = $tag;
        $this->selfClosing  = in_array($tag, array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'));
    }

    public function before($content)
    {
        if(is_array($content)) $this->prefix = array_merge($this->prefix, $content);
        else $this->prefix[] = $content;
        return $this;
    }

    public function after($content)
    {
        if(is_array($content)) $this->suffix = array_merge($this->suffix, $content);
        else $this->suffix[] = $content;
        return $this;
    }

    public function empty()
    {
        $this->children = [];
        return $this;
    }

    public function append($content)
    {
        if(is_array($content)) $this->children = array_merge($this->children, $content);
        else $this->children[] = $content;

        return $this;
    }

    public function prepend($content)
    {
        if(is_array($content)) $this->children = array_merge($content, $this->children);
        else array_unshift($this->children, $content);

        return $this;
    }

    public function props($props, $reset = false)
    {
        if($reset) $this->propsStr = '';

        $propsStr = trim(strval($props));
        if(!empty($propsStr)) $this->propsStr .= ' ' . $propsStr;
        return $this;
    }

    public function js($code)
    {
        if(is_array($code)) $this->jsCode = array_merge($this->jsCode, $code);
        else $this->jsCode[] = $code;
        return $this;
    }

    public function css($code)
    {
        if(is_array($code)) $this->cssCode = array_merge($this->cssCode, $code);
        else $this->cssCode[] = $code;
        return $this;
    }

    public function importJs($jsFile)
    {
        if(is_array($jsFile)) $this->jsImports = array_merge($this->jsImports, $jsFile);
        else $this->jsImports[] = $jsFile;
        return $this;
    }

    public function importCss($cssFile)
    {
        if(is_array($cssFile)) $this->cssImports = array_merge($this->cssImports, $cssFile);
        else $this->cssImports[] = $cssFile;
        return $this;
    }

    public function selfClose($selfClosing = true)
    {
        $this->selfClosing = $selfClosing;
        return $this;
    }

    public function renderInTag($inTag = false)
    {
        $this->inTag = $inTag;
        return $this;
    }

    public function build()
    {
        $html = array();

        if(!$this->selfClosing && $this->inTag && !empty($this->tag)) $html[] = "<$this->tag" . "$this->propsStr>";

        if(!empty($this->prefix)) $html = array_merge($html, $this->prefix);

        if(!empty($this->cssImports))
        {
            foreach($this->cssImports as $href)
            {
                if(!empty($href)) $html[] = "<link rel=\"stylesheet\" href=\"$href\">";
            }
        }

        if(!empty($this->cssCode))
        {
            $cssCode = '';
            foreach($this->cssCode as $css)
            {
                if(!empty($css)) $cssCode .= $css;
            }
            if(!empty($cssCode)) $html[] = "<style>$cssCode</style>";
        }

        if($this->selfClosing)
        {
            if(!empty($this->tag)) $html[] = "<$this->tag" . "$this->propsStr />";
        }
        else
        {
            $innerHtml = '';
            if(!empty($this->tag) && !$this->inTag) $innerHtml .= "<$this->tag" . "$this->propsStr>";
            if(!empty($this->children)) $innerHtml .= implode("\n", $this->children);
            if(!empty($this->tag) && !$this->inTag) $innerHtml .= "</$this->tag>";
            if(!empty($innerHtml)) $html[] = $innerHtml;
        }

        if(!empty($this->suffix)) $html = array_merge($html, $this->suffix);

        $jsCode = '';
        if(!empty($this->jsVars))
        {
            foreach($this->jsVars as $var => $val)
            {
                if(empty($var)) continue;
                if(strpos($var, 'window.') === 0) $jsCode .= "$var=" . json_encode($val) . ';';
                else $jsCode .= "var $var=" . json_encode($val) . ';';
            }
        }
        if(!empty($this->jsImports))
        {
            foreach($this->jsImports as $src)
            {
                if(!empty($src)) $html[] = "<script src=\"$src\"></script>";
            }
        }
        if(!empty($this->jsCode))
        {
            foreach($this->jsCode as $js)
            {
                if(!empty($js)) $jsCode .= $js;
            }
        }
        if(!empty($jsCode)) $html[] = "<script>(function(){$jsCode}</script>";

        if(!$this->selfClosing && $this->inTag && !empty($this->tag)) $html[] = "</$this->tag>";

        return implode('', $html);
    }

    /**
     * Create html builder instance
     *
     * @param array $tag - Element tag name
     * @return builder
     */
    static public function new($tag = '')
    {
        return new builder($tag);
    }
}
