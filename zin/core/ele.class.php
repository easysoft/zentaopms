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

namespace zin\core;

require_once 'props.class.php';
require_once 'builder.class.php';
require_once 'data.class.php';

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

    protected $parent;

    public $class;

    public $style;

    public $hx;

    public $data;

    /**
     * Whether the HTML has been printed to the page
     *
     * @access protected
     * @var bool
     */
    protected $printed = false;

    public function __construct(/* $tagName, $props = NULL, ...$children = NULL */)
    {
        if(static::$tag)
        {
            $this->tagName = static::$tag;
        }
        else
        {
            $className = get_called_class();
            $this->tagName = ($className !== 'ele' && $className !== 'wg') ? $className : '';
        }

        $this->props   = new props(NULL, static::$customProps);
        $this->class   = $this->props->class;
        $this->style   = $this->props->style;
        $this->hx      = $this->props->hx;
        $this->data    = new data();

        if(is_array(static::$defaultProps)) $this->setDefaultProps(static::$defaultProps);

        $this->append(func_get_args());

        if(method_exists($this, 'init'))      $this->init();
        if(method_exists($this, 'onCreated')) $this->onCreated();
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
        $builder = $this->build($isPrint, $parent || $this->parent);

        if(method_exists($this, 'onBuild'))
        {
            $newBuilder = $this->onBuild($builder);
            if($newBuilder) $builder = $newBuilder;
        }

        $htmlCode = $builder->build();

        return $htmlCode;
    }

    /**
     * Print html to page
     * This function can only be called once
     *
     * @param callable $callback Callback before print
     * @return wg
     */
    public function print()
    {
        $html = $this->render(true, NULL);

        if(method_exists($this, 'onPrint'))
        {
            $newHtml = $this->onPrint($html);
            if(is_string($newHtml)) $html = $newHtml;
        }

        echo $html;

        $this->printed = true;

        return $this;
    }

    public function x()
    {
        return $this->print();
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
                if(is_object($child))
                {
                    if(method_exists($child, 'render')) $html[] = $child->render($isPrint, $this);
                    else if(isset($child->html)) $html[] = $child->html;
                }
                else if(is_array($child))
                {
                    $html[] = implode('', $child);
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

    protected function acceptChild($child, $strAsHtml = false)
    {
        if($child instanceof ele)
        {
            $child->parent = &$this;
            return $child;
        }
        if($child instanceof props)
        {
            $this->props->merge($child);
            return NULL;
        }
        if($child instanceof style)
        {
            $this->style->merge($child);
            return NULL;
        }
        if($child instanceof classlist)
        {
            $this->class->set($child->list);
            return NULL;
        }
        if($child instanceof hx)
        {
            $this->hx->merge($child);
            return NULL;
        }

        if(is_object($child))
        {
            if(isset($child->set))
            {
                $this->prop($child->set);
                unset($child->set);
            }
            if(isset($child->class))
            {
                $this->addClass($child->class);
                unset($child->class);
            }
            if(isset($child->style))
            {
                $this->setStyle($child->style);
                unset($child->style);
            }
            if(isset($child->hx))
            {
                $this->hxSet($child->hx);
                unset($child->hx);
            }
            if(isset($child->tag))
            {
                $this->setTag($child->tag);
                unset($child->tag);
            }
            if(isset($child->data))
            {
                $this->setData($child->data);
                unset($child->data);
            }
            $child->custom = true;
        }
        if($strAsHtml && is_string($child))
        {
            $htmlInfo = new \stdClass();
            $htmlInfo->type = 'html';
            $htmlInfo->html = $child;
            return $htmlInfo;
        }
        return $child;
    }

    public function setTag($tagName)
    {
        $this->tagName = $tagName;
        return $this;
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

            $child = $this->acceptChild($child, $strAsHtml);

            if(empty($child) || (is_object($child) && isset($child->custom) && $child->custom && !isset($child->html))) continue;

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
        if($value === NULL && is_string($name)) return $this->props->get($name);

        $this->props->set($name, $value);
        return $this;
    }

    public function setDefaultProps($props)
    {
        if(is_array($props))
        {
            foreach($props as $name => $value)
            {
                if($this->props->has($name)) continue;
                $this->props->set($name, $value);
            }
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

    public function hxSet($prop, $value = NULL, $removeEmpty = false)
    {
        $this->hx->set($prop, $value, $removeEmpty);
        return $this;
    }

    public function setData()
    {
        $this->data->set(func_get_args());
        return $this;
    }

    public function getData($name, $defaultValue = NULL)
    {
        return $this->data->get($name, $defaultValue);
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
    static public function str()
    {
        return (new ele(func_get_args()))->toStr();
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
