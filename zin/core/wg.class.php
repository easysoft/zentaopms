<?php
/**
 * The base widget class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'props.class.php';
require_once 'wg.func.php';
require_once 'directive.func.php';

class wg
{
    /**
     * Define props for the element
     *
     * @todo @sunhao: Support for using string
     * @var array|string
     */
    protected static $defineProps = NULL;

    protected static $defineBlocks = NULL;

    private static $definedPropsMap = array();

    /**
     * The props of the element
     *
     * @access public
     * @var    props
     */
    public $props;

    public $parent = NULL;

    public function __construct(/* string|element|object|array|null ...$args */)
    {
        $this->props = new props();

        $this->setDefaultProps(static::getDefaultProps());
        $this->append(func_get_args());
        $this->created();
    }

    /**
     * Render element to html
     * @return string
     */
    public function render()
    {
        $before   = $this->buildBefore();
        $after    = $this->buildAfter();
        $children = $this->build();

        return static::renderToHtml(array($before, $after, $children), $this);
    }

    public function print()
    {
        $html = $this->render(true);
        echo $html;
        return $this;
    }

    protected function buildBefore()
    {
        return $this->props->getBlock('before');
    }

    protected function buildAfter()
    {
        return $this->props->getBlock('after');
    }

    protected function build()
    {
        return $this->props->getChildren();
    }

    public function block($name)
    {
        return $this->props->getBlock($name);
    }

    public function append($items)
    {
        if(empty($items)) return;

        foreach($items as $item)
        {
            if($item === NULL) continue;

            if(($item instanceof wg))
            {
                $this->addChild($item);
                continue;
            }
            if(is_string($item))
            {
                $this->addChild(htmlentities($item));
                continue;
            }
            if(is_array($item))
            {
                $this->append($item);
                continue;
            }
            if(isDirective($item))
            {
                $this->directive($item);
                continue;
            }
        }
    }

    public function addChild($child)
    {
        if($child instanceof wg) $child->parent = $this;
        $this->props->addChildren($child);
    }

    public function before($child)
    {
        $this->addToBlock('before', $child);
    }

    public function after($child)
    {
        $this->addToBlock('after', $child);
    }

    public function addToBlock($name, $child = NULL)
    {
        if(is_array($name))
        {
            foreach($name as $blockName => $blockChildren)
            {
                $this->addToBlock($blockName, $blockChildren);
            }
            return;
        }
        if(is_array($child))
        {
            foreach($child as $blockChild)
            {
                $this->addToBlock($name, $blockChild);
            }
            return;
        }

        if($child instanceof wg) $child->parent = $this;

        $this->props->addToBlock($name, $child);
    }

    /**
     * Apply directive
     * @param object $directive
     */
    public function directive($directive)
    {
        $data = $directive->data;
        $type = $directive->type;

        if($type === 'prop')
        {
            $this->props->set($data);
            return;
        }
        if($type === 'class')
        {
            $this->props->class->set($data);
            return;
        }
        if($type === 'style')
        {
            $this->props->style->set($data);
            return;
        }
        if($type === 'cssVar')
        {
            $this->props->style->var($data);
            return;
        }
        if($type === 'html')
        {
            $this->addChild($data);
            return;
        }
        if($type === 'text')
        {
            $this->addChild(htmlspecialchars($data));
            return;
        }
        if($type === 'block')
        {
            $this->addToBlock($data);
            return;
        }
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @param bool           $removeEmpty - Whether to remove empty value
     * @return dataset
     */
    public function prop($prop, $value = '%%%NULL%%%')
    {
        if(is_array($prop))
        {
            $this->props->set($prop);
            return $this;
        }

        if(is_string($prop) && $value === '%%%NULL%%%')
        {
            return $this->props->get($prop);
        }

        $this->props->set($prop, $value);
        return $this;
    }

    public function setDefaultProps($props)
    {
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->has($name)) continue;
            if($name === 'id' && $value === '$GID') $value = 'zin-' . uniqid();
            $this->props->set($name, $value);
        }
    }

    protected function created() {}

    protected function toJsonData()
    {
        $data = $this->props->toJsonData();
        foreach($data as $key => $value)
        {
            if($key[0] !== '#') continue;
            foreach($value as $index => $child)
            {
                if($child instanceof wg || (is_object($child) && method_exists($child, 'toJsonData')))
                {
                    $value[$index] = $child->toJsonData();
                }
            }
            $data[$key] = $value;
        }
        $data['$type'] = get_called_class();
        if(strpos($data['$type'], 'zin\\') === 0) $data['$type'] = substr($data['$type'], 4);
        return $data;
    }

    protected static function getDefaultProps()
    {
        $defaultProps = array();
        foreach(static::getDefinedProps() as $name => $definition)
        {
            if(!isset($definition['default'])) continue;
            $defaultProps[$name] = $definition['default'];
        }
        return $defaultProps;
    }

    protected static function getDefinedProps()
    {
        $name = get_called_class();
        if(!isset(self::$definedPropsMap[$name]))
        {
            self::$definedPropsMap[$name] = is_string(static::$defineProps) || is_array(static::$defineProps)
                ? defineProps(static::$defineProps)
                : array();
        }
        return self::$definedPropsMap[$name];
    }

    /**
     * @return string
     */
    public static function renderToHtml($children)
    {
        $html = array();
        foreach($children as $child)
        {
            if($child === NULL) continue;

            if(is_array($child))
            {
                $html[] = static::renderToHtml($child, );
            }
            elseif($child instanceof wg)
            {
                $html[] = $child->render();
            }
            elseif(is_string($child))
            {
                $html[] = $child;
            }
            elseif(is_object($child))
            {
                if(method_exists($child, 'render')) $html[] = $child->render();
                elseif(isset($child->html))         $html[] = $child->html;
                elseif(isset($child->text))         $html[] = htmlspecialchars($child->text);
                else                                $html[] = strval($child);
            }
            else
            {
                $html[] = strval($child);
            }
        }
        return implode('', $html);
    }
}
