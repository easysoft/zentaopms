<?php
/**
 * The base element class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

require_once 'props.class.php';
require_once 'builder.class.php';

class ele
{
    protected static $tag = NULL;

    protected static $defaultProps = NULL;

    protected static $customProps = NULL;

    protected static $selfClosing = NULL;

    protected static $asBlock = NULL;

    public $props;

    public $tagName;

    public $children = array();

    public $parent;

    public $class;

    public $style;

    public $hx;

    /**
     * Callback for before render
     * @var string
     * @access protected
     */
    protected $beforeRenderCallback;

    /**
     * Callback for after render
     * @var string
     * @access protected
     */
    protected $afterRenderCallback;

    /**
     * Whether the HTML has been printed to the page
     *
     * @access protected
     * @var bool
     */
    protected $printed = false;

    public function __construct(/* $tagName, $props = NULL, ...$children = NULL */)
    {
        /* Use all args */
        list($tagName, $props, $children) = static::parseArgs(func_get_args());

        $this->tagName = $tagName;
        $this->props   = new props($props, static::$customProps);
        $this->class   = $this->props->class;
        $this->style   = $this->props->style;
        $this->hx      = $this->props->hx;

        if(!empty($children)) $this->append($children);

        if(is_array(static::$defaultProps)) $this->setDefaultProps(static::$defaultProps);

        if(method_exists($this, 'init')) $this->init();
    }

    /**
     * Override __set
     *
     * @access public
     * @param string $prop  - Property name
     * @param mixed  $value - Property value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->props->set($name, $value);
    }

    /**
     * Override __get
     *
     * @access public
     * @param string $prop - Property name
     * @return mixed
     */
    public function __get($name)
    {
        $this->props->get($name);
    }

    /**
     * Convert to html string
     *
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->toStr();
    }

    /**
     * Override __call to invoke toggle method conveniently
     *
     * Example:
     *
     *     $classlist = classlist::new();
     *
     *     // Add "primary" class
     *     $classlist->primary();
     *
     *     // Remove "primary" class
     *     $classlist->primary(false);
     *
     * @access public
     * @return classlist
     */
    public function __call($name, $args)
    {
        $this->class->toggle($name, !count($args) || $args[0]);
        return $this;
    }

    public function render($isPrint = false, $parent = NULL)
    {
        if($parent === NULL) $parent = $this->parent;

        $builder = $this->build($isPrint, $parent);

        if(is_callable($this->beforeRenderCallback))
        {
            call_user_func($this->beforeRenderCallback, $builder, $this);
        }

        $htmlCode = $builder->build();

        if(is_callable($this->afterRenderCallback))
        {
            $htmlCode = call_user_func($this->afterRenderCallback, $htmlCode, $this);
        }
        return $htmlCode;
    }

    /**
     * Print html to page
     * This function can only be called once
     *
     * @param callable $callback Callback before print
     * @return wg
     */
    public function print($callback = NULL)
    {
        $html = $this->render(NULL, true);

        if(is_callable($callback))
        {
            $html = call_user_func($callback, $html);
        }

        echo $html;

        $this->printed = true;

        return $this;
    }

    public function x($callback = NULL)
    {
        return $this->print($callback);
    }

    protected function buildInnerHtml($isPrint = false, $parent = NULL)
    {
        return $this->buildChildren($this->children, $isPrint, $parent);
    }

    protected function buildChildren($children, $isPrint, $parent = NULL)
    {
        $html = array();

        if(!empty($children))
        {
            if(!is_array($children)) $children = array($children);

            foreach($children as $child)
            {
                if(is_object($child) && method_exists($child, 'render'))
                {
                    $html[] = $child->render($isPrint, $this);
                }
                else if(is_array($child))
                {
                    if(isset($child['html'])) $html[] = $child['html'];
                    else $html[] = implode('', $child);
                }
                else
                {
                    $html[] = htmlspecialchars(strval($child));
                }
            }
        }

        return $html;
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = builder::new($this->tagName)->props($this->props);

        if(static::$selfClosing === true) $builder->selfClose(true);
        else $builder->append($this->buildInnerHtml($isPrint, $parent));

        return $builder;
    }

    /**
     * Add children to element
     *
     * @param mixed $children
     */
    public function add($children, $prepend = false, $strAsHtml = false, $reset = false)
    {
        if($reset) $this->children = array();

        if(empty($children)) return $this;

        if(!is_array($children)) $children = array($children);

        foreach($children as $child)
        {
            if(is_array($child))
            {
                $this->add($child, $prepend, $strAsHtml, $reset);
                continue;
            }

            if($child instanceof ele) $child->parent = $this;
            else if($strAsHtml && is_string($child)) $child = array('html' => $child);

            if($prepend) array_unshift($this->children, $child);
            else $this->children[] = $child;
        }

        return $this;
    }

    public function append()
    {
        return $this->add(func_get_args(), false);
    }

    public function prepend()
    {
        return $this->add(func_get_args(), true);
    }

    public function appendHtml()
    {
        return $this->add(func_get_args(), false, true);
    }

    public function prependHtml()
    {
        return $this->add(func_get_args(), true, true);
    }

    /**
     * Get or set inner text
     *
     * @param string $text
     * @return wg Return self for chain calls.
     */
    public function text($text = NULL, $reset = true)
    {
        if($text === NULL)
        {
            $textList = array();
            foreach($this->children as $child)
            {
                if(is_object($child) && method_exists($child, 'text'))
                {
                    $textList[] = $child->text();
                }
                elseif(is_array($child) && isset($child['html']))
                {
                    $textList[] = htmlentities($child['html']);
                }
                else
                {
                    $textList[] = strval($child);
                }
            }

            return implode('\n', $textList);
        }

        return $this->add($text, false, false, $reset);
    }

    /**
     * Get or set inner html
     *
     * @param string $html
     * @return wg Return self for chain calls.
     */
    public function html($html = NULL, $reset = true)
    {
        if($html === NULL) return implode('\n', $this->buildInnerHtml(false));

        return $this->add($html, false, true, $reset);
    }

    /**
     * Set style property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return wg
     */
    public function setStyle($prop, $value = NULL, $removeEmpty = false)
    {
        $this->style->set($prop, $value, $removeEmpty);
        return $this;
    }

    public function prop($name, $value = NULL)
    {
        if($value === NULL) return $this->props->get($name);

        $this->props->set($name, $value);
        return $this;
    }

    public function setDefaultProps($props)
    {
        foreach($props as $name => $value)
        {
            if($this->props->has($name)) continue;
            $this->props->set($name, $value);
        }
        return $this;
    }

    /**
     * Remove className
     *
     * @param string $classlist
     * @return wg Return self for chain calls.
     */
    public function removeClass($classlist)
    {
        $this->class->remove($classlist);
        return $this;
    }

    /**
     * Add className
     *
     * @param string|object|array $className
     * @return wg Return self for chain calls.
     */
    public function addClass($className, $reset = false)
    {
        $this->class->set($className, $reset);
        return $this;
    }

    /**
     * Check the given class name
     *
     * @param string $className
     * @return boolean
     */
    public function hasClass($className)
    {
        return $this->class->has($className);
    }

    /**
     * Toggle class name
     *
     * @param string $className
     * @param boolean $toggle
     * @return wg Return self for chain calls.
     */
    public function toggleClass($className, $toggle = NULL)
    {
        if($toggle === NULL) $toggle = !$this->hasClass($className);

        if($toggle) $this->addClass($className);
        else        $this->removeClass($className);

        return $this;
    }

    /**
     * Set callback for before render
     *
     * @param callable $callback
     * @return wg Return self for chain calls.
     */
    public function beforeRender($callback)
    {
        $this->beforeRenderCallback = $callback;
        return $this;
    }

    /**
     * Set callback for after render
     *
     * @param callable $callback
     * @return wg Return self for chain calls.
     */
    public function afterRender($callback)
    {
        $this->afterRenderCallback = $callback;
        return $this;
    }

    public function toStr()
    {
        return $this->render();
    }

    /**
     * Create an instance of ele
     *
     * @return ele
     */
    static public function new()
    {
        return new ele(func_get_args());
    }

    /**
     * Stringify ele to html
     *
     * @access public
     * @return string
     */
    static public function str($tagName, $props = NULL, $children = NULL)
    {
        return (new ele($tagName, $props, $children))->toStr();
    }

    static protected function parseArgs($args)
    {
        $tagName  = '';
        $props    = array();
        $children = array();

        if(is_string(static::$tag))
        {
            if(count($args))
            {
                if(is_array($args[0])) array_unshift($args[0], static::$tag);
                else array_unshift($args, static::$tag);
            }
        }

        foreach($args as $argIdx => $arg)
        {
            if($argIdx === 0)
            {
                if(is_array($arg))
                {
                    foreach($arg as $i => $a)
                    {
                        if($i === 0)
                        {
                            $tagName = $a;
                        }
                        elseif($i === 1)
                        {
                            if($a !== NULL && !is_array($a)) $children[] = $a;
                            else $props = array_merge($props, $a);
                        }
                        else
                        {
                            $children[] = $a;
                        }
                    }
                }
                else
                {
                    $tagName = $arg;
                }
            }
            elseif($argIdx === 1)
            {
                /* Make $props optional */
                if($arg !== NULL && !is_array($arg)) $children[] = $arg;
                else $props = array_merge($props, $arg);
            }
            else
            {
                $children[] = $arg;
            }
        }

        if($tagName[0] === '<')
        {
            /* TODO: @sunhao parse name, props and children from string */
        }

        return array($tagName, $props, $children);
    }

    static protected function isBlockTag($tag = '')
    {
        $isAsBlock = static::$asBlock;
        if(is_bool($isAsBlock)) return $isAsBlock;

        return !in_array($tag, array('a', 'span', 'strong', 'small', 'i', 'em', 'code'));
    }

    /**
     * @return builder
     */
    static public function createBuilder($tag = '')
    {
        return new builder($tag);
    }

    /**
     * @return props
     */
    static public function createProps($data = NULL)
    {
        return new props($data);
    }

    /**
     * @return classlist
     */
    static public function createClass($data = NULL)
    {
        return new classlist($data);
    }

    /**
     * @return style
     */
    static public function createStyle($data = NULL)
    {
        return new style($data);
    }
}
