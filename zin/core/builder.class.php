<?php
/**
 * The builder class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\core;

require_once 'props.class.php';

/**
 * HTML builder
 */
class builder
{
    /**
     * TagName
     * @var string
     */
    public $tag = '';

    /**
     * Children of element
     * @var array
     */
    public $children = array();

    /**
     * Props of element
     * @var props
     */
    public $props;

    /**
     * @var array
     */
    public $prefix = array();

    /**
     * @var array
     */
    public $suffix = array();

    /**
     * Js code
     * @var array
     */
    public $jsCode = array();

    /**
     * Css code
     * @var array
     */
    public $cssCode = array();

    /**
     * Js files
     * @var array
     */
    public $jsImports = array();

    /**
     * Css files
     * @var array
     */
    public $cssImports = array();

    /**
     * Js vars
     * @var array
     */
    public $jsVars = array();

    /**
     * Whether it is a self-closing tag
     * @var bool
     */
    public $selfClosing;

    /**
     * Whether to use tag rendering
     * @var bool
     */
    public $inTag = false;

    public function __construct($tag = '', $props = NULL)
    {
        $this->tag          = $tag;
        $this->props        = $props ? $props->clone() : new props();
        $this->selfClosing  = in_array($tag, array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'));
    }

    /**
     * Set element tag
     *
     * @param string $tag
     * @return builder
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Set prefix
     *
     * @param string|array $content
     * @return builder
     */
    public function before($content)
    {
        if(is_array($content)) $this->prefix = array_merge($this->prefix, $content);
        else $this->prefix[] = $content;
        return $this;
    }

    /**
     * Set suffix
     *
     * @param string|array $content
     * @return builder
     */
    public function after($content)
    {
        if(is_array($content)) $this->suffix = array_merge($this->suffix, $content);
        else $this->suffix[] = $content;
        return $this;
    }

    /**
     * Clear children
     *
     * @return builder
     */
    public function empty()
    {
        $this->children = array();
        return $this;
    }

    /**
     * Append children
     *
     * @param string|array $content
     * @return builder
     */
    public function append($content)
    {
        if(is_array($content)) $this->children = array_merge($this->children, $content);
        else $this->children[] = $content;

        return $this;
    }

    /**
     * Prepend children
     *
     * @param string|array $content
     * @return builder
     */
    public function prepend($content)
    {
        if(is_array($content)) $this->children = array_merge($content, $this->children);
        else array_unshift($this->children, $content);

        return $this;
    }

    /**
     * Get or set prop
     *
     * @param string $name
     * @param mixed $value
     * @return builder
     */
    public function prop($name, $value = NULL)
    {
        if($value === NULL && is_string($name)) return $this->props->get($name);

        $this->props->set($name, $value);
        return $this;
    }

    /**
     * Inject js code
     *
     * @param string|array
     * @return builder
     */
    public function js($code)
    {
        if(is_array($code)) $this->jsCode = array_merge($this->jsCode, $code);
        else $this->jsCode[] = $code;
        return $this;
    }

    /**
     * Inject css code
     *
     * @param string|array
     * @return builder
     */
    public function css($code)
    {
        if(is_array($code)) $this->cssCode = array_merge($this->cssCode, $code);
        else $this->cssCode[] = $code;
        return $this;
    }

    /**
     * Import js file
     *
     * @param string|array $jsFile
     * @return builder
     */
    public function importJs($jsFile)
    {
        if(is_array($jsFile)) $this->jsImports = array_merge($this->jsImports, $jsFile);
        else $this->jsImports[] = $jsFile;
        return $this;
    }

    /**
     * Append js var
     *
     * @param string|array $name
     * @param mixed $value
     * @return void
     */
    public function jsVar($name, $value = NULL)
    {
        if(is_array($name)) $this->jsVars = array_merge($this->jsVars, $name);
        $this->jsVars[$name] = $value;
    }

    /**
     * Import css file
     *
     * @param string|array $cssFile
     * @return builder
     */
    public function importCss($cssFile)
    {
        if(is_array($cssFile)) $this->cssImports = array_merge($this->cssImports, $cssFile);
        else $this->cssImports[] = $cssFile;
        return $this;
    }

    /**
     * Whether it is a self-closing tag
     *
     * @param bool $selfClosing
     * @return builder
     */
    public function selfClose($selfClosing = true)
    {
        $this->selfClosing = $selfClosing;
        return $this;
    }

    /**
     * Whether to use tag rendering
     *
     * @param bool $inTag
     * @return builder
     */
    public function renderInTag($inTag = true)
    {
        $this->inTag = $inTag;
        return $this;
    }

    /**
     * Build element
     */
    public function build()
    {
        $html = array();

        /** Get props string */
        $propsStr = $this->props->toStr();
        if(!empty($propsStr)) $propsStr = " $propsStr";

        /** Reander tag */
        if(!$this->selfClosing && $this->inTag && !empty($this->tag)) $html[] = "<$this->tag" . "$propsStr>";

        /** Handle prefix */
        if(!empty($this->prefix)) $html = array_merge($html, $this->prefix);

        /** Use link to import css file */
        if(!empty($this->cssImports))
        {
            foreach($this->cssImports as $href)
            {
                if(!empty($href)) $html[] = "<link rel=\"stylesheet\" href=\"$href\">";
            }
        }

        /** Use <style> to add css code */
        if(!empty($this->cssCode))
        {
            $cssCode = '';
            foreach($this->cssCode as $css)
            {
                if(!empty($css)) $cssCode .= $css;
            }
            if(!empty($cssCode)) $html[] = "<style>$cssCode</style>";
        }

        /** Handle self-closing tag */
        if($this->selfClosing)
        {
            if(!empty($this->tag)) $html[] = "<$this->tag" . "$propsStr />";
        }
        else
        {
            $innerHtml = '';
            if(!empty($this->tag) && !$this->inTag) $innerHtml .= "<$this->tag" . "$propsStr>";
            if(!empty($this->children)) $innerHtml .= implode("\n", $this->children);
            if(!empty($this->tag) && !$this->inTag) $innerHtml .= "</$this->tag>";
            if(!empty($innerHtml)) $html[] = $innerHtml;
        }

        /** Handle suffix */
        if(!empty($this->suffix)) $html = array_merge($html, $this->suffix);

        /** Inject js var */
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

        /** Use <script src> to import js file */
        if(!empty($this->jsImports))
        {
            foreach($this->jsImports as $src)
            {
                if(!empty($src)) $html[] = "<script src=\"$src\"></script>";
            }
        }

        /** Use IIFE to inject js code */
        if(!empty($this->jsCode))
        {
            foreach($this->jsCode as $js)
            {
                if(!empty($js)) $jsCode .= $js;
            }
        }
        if(!empty($jsCode)) $html[] = "<script>(function(){ $jsCode }())</script>";

        if(!$this->selfClosing && $this->inTag && !empty($this->tag)) $html[] = "</$this->tag>";

        return implode('', $html);
    }

    /**
     * Create html builder instance
     *
     * @param array $tag - Element tag name
     * @return builder
     */
    static public function new($tag = '', $props = NULL)
    {
        return new builder($tag, $props);
    }
}
