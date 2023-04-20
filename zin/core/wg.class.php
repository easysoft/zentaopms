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
require_once 'directive.class.php';
require_once 'zin.class.php';
require_once 'context.class.php';
require_once 'selector.func.php';
require_once 'dom.class.php';

class wg
{
    /**
     * Define props for the element
     *
     * @todo @sunhao: Support for using string
     * @var array|string
     */
    protected static $defineProps = NULL;

    protected static $defaultProps = NULL;

    protected static $defineBlocks = NULL;

    protected static $wgToBlockMap = array();

    protected static $definedPropsMap = array();

    private static $gidSeed = 0;

    private static $pageResources = array();

    /**
     * The props of the element
     *
     * @access public
     * @var    props
     */
    public $props;

    public $blocks = array();

    public $parent = NULL;

    public $gid;

    public $displayed = false;

    protected $matchedPortals = NULL;

    protected $renderOptions = NULL;

    public function __construct(/* string|element|object|array|null ...$args */)
    {
        $this->props = new props();

        $this->gid = self::nextGid();
        $this->setDefaultProps(static::getDefaultProps());
        $this->add(func_get_args());
        $this->created();

        zin::renderInGlobal($this);
        static::checkPageResources();
    }

    public function __debugInfo()
    {
        return $this->toJsonData();
    }

    public function isDomElement()
    {
        return false;
    }

    /**
     * Check if the element is match any of the selectors
     * @param  string|array|object $selectors
     */
    public function isMatch($selectors)
    {
        $list = parseWgSelectors($selectors);
        foreach($list as $selector)
        {
            if(isset($selector->command)) continue;
            if(!empty($selector->id)    && $this->id() !== $selector->id) continue;
            if(!empty($selector->tag)   && $this->shortType() !== $selector->tag) continue;
            if(!empty($selector->class) && !$this->props->class->has($selector->class)) continue;
            return true;
        }
        return false;
    }

    protected function checkPortals()
    {
        $this->matchedPortals = array();
        $portals = context::current()->getPortals();
        foreach($portals as $portal)
        {
            if($this->isMatch($portal->prop('target'))) $this->matchedPortals[] = $portal->children();
        }
    }

    protected function getPortals()
    {
        $portals = $this->matchedPortals;
        $this->matchedPortals = NULL;
        return $portals;
    }

    /**
     * Build dom object
     * @return dom
     */
    public function buildDom()
    {
        $this->checkPortals();

        $before    = $this->buildBefore();
        $children  = $this->build();
        $after     = $this->buildAfter();
        $portals   = $this->getPortals();
        $options   = $this->renderOptions;
        $selectors = (!empty($options) && isset($options['selector'])) ? $options['selector'] : NULL;

        return new dom
        (
            $this,
            [$before, $children, $portals, $after],
            $selectors,
            (!empty($options) && isset($options['type'])) ? $options['type'] : 'html',
            (!empty($options) && isset($options['data'])) ? $options['data'] : NULL,
        );
    }

    /**
     * Render widget to html
     * @return string
     */
    public function render()
    {
        $dom  = $this->buildDom();
        $html = $dom->render();

        context::destroy($this->gid);

        return $html;
    }

    public function display($options = [])
    {
        zin::disableGlobalRender();

        $this->renderOptions = $options;

        echo $this->render();

        $this->displayed = true;
        return $this;
    }

    protected function created() {}

    protected function buildBefore()
    {
        return $this->block('before');
    }

    protected function buildAfter()
    {
        return $this->block('after');
    }

    protected function build()
    {
        return  $this->children();
    }

    protected function onAddBlock($child, $name)
    {
        return $child;
    }

    protected function onAddChild($child)
    {
        return $child;
    }

    protected function onSetProp($prop, $value)
    {
        if($prop === 'id' && $value === '$GID') $value = $this->gid;
        $this->props->set($prop, $value);
    }

    protected function onGetProp($prop, $defaultValue)
    {
        return $this->props->get($prop, $defaultValue);
    }

    public function add($item, $blockName = 'children')
    {
        if($item === NULL || is_bool($item)) return $this;

        if(is_array($item))
        {
            foreach($item as $child) $this->add($child, $blockName);
            return $this;
        }

        zin::disableGlobalRender();

        if($item instanceof wg)    $this->addToBlock($blockName, $item);
        elseif(is_string($item))   $this->addToBlock($blockName, htmlentities($item));
        elseif(isDirective($item)) $this->directive($item, $blockName);
        else                       $this->addToBlock($blockName, htmlentities(strval($item)));

        zin::enableGlobalRender();

        return $this;
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

        if($child instanceof wg && empty($child->parent)) $child->parent = &$this;
        if($child instanceof wg && $child->type() === 'zin\portal') return;

        if($name === 'children' && $child instanceof wg)
        {
            $blockName = static::getBlockNameForWg($child);
            if($blockName !== NULL) $name = $blockName;
        }

        $result = $name === 'children' ? $this->onAddChild($child) : $this->onAddBlock($child, $name);

        if($result === false) return;
        if($result !== NULL && $result !== true) $child = $result;

        if(isset($this->blocks[$name])) $this->blocks[$name][] = $child;
        else $this->blocks[$name] = array($child);
    }

    public function children()
    {
        return $this->block('children');
    }

    public function block($name)
    {
        return isset($this->blocks[$name]) ? $this->blocks[$name] : array();
    }

    public function hasBlock($name)
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Apply directive
     * @param object $directive
     */
    public function directive(&$directive, $blockName)
    {
        $data = $directive->data;
        $type = $directive->type;
        $directive->parent = &$this;

        if($type === 'prop')
        {
            $this->setProp($data);
            return;
        }
        if($type === 'class' || $type === 'style')
        {
            $this->setProp($type, $data);
            return;
        }
        if($type === 'cssVar')
        {
            $this->setProp('--', $data);
            return;
        }
        if($type === 'html')
        {
            $this->addToBlock($blockName, $directive);
            return;
        }
        if($type === 'text')
        {
            $this->addToBlock($blockName, htmlspecialchars($data));
            return;
        }
        if($type === 'block')
        {
            foreach($data as $blockName => $blockChildren)
            {
                $this->add($blockChildren, $blockName);
            }
            return;
        }
    }

    public function prop($name, $defaultValue = NULL)
    {
        if(is_array($name))
        {
            $values = array();
            foreach($name as $index => $propName)
            {
                $values[] = $this->onGetProp($propName, is_array($defaultValue) ? (isset($defaultValue[$propName]) ? $defaultValue[$propName] : $defaultValue[$index]) : $defaultValue);
            }
            return $values;
        }

        return $this->onGetProp($name, $defaultValue);
    }

    /**
     * Set property, an array can be passed to set multiple properties
     *
     * @access public
     * @param array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     * @return dataset
     */
    public function setProp($prop, $value = NULL)
    {
        if($prop instanceof props) $prop = $prop->toJsonData();

        if(is_array($prop))
        {
            foreach($prop as $name => $value) $this->setProp($name, $value);
            return $this;
        }

        if(!is_string($prop) || empty($prop)) return $this;

        if($prop[0] === '#')
        {
            $this->add($value, substr($prop, 1));
            return;
        }

        $this->onSetProp($prop, $value);
        return $this;
    }

    public function hasProp()
    {
        $names = func_get_args();
        if(empty($names)) return false;
        foreach ($names as $name) if(!$this->props->has($name)) return false;
        return true;
    }

    public function setDefaultProps($props)
    {
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->has($name)) continue;
            $this->setProp($name, $value);
        }
    }

    public function getRestProps()
    {
        return $this->props->skip(array_keys(static::getDefinedProps()));
    }

    public function type()
    {
        return get_called_class();
    }

    public function shortType()
    {
        $type = $this->type();
        $pos = strrpos($type, '\\');
        return $pos === false ? $type : substr($type, $pos + 1);
    }

    public function id()
    {
        return $this->prop('id');
    }

    public function toJsonData()
    {
        $data = array();
        $data['gid'] = $this->gid;
        $data['props'] = $this->props->toJsonData();

        $data['type'] = $this->type();
        if(str_starts_with($data['type'], 'zin\\')) $data['type'] = substr($data['type'], 4);

        $data['blocks'] = array();
        foreach($this->blocks as $key => $value)
        {
            foreach($value as $index => $child)
            {
                if($child instanceof wg || (is_object($child) && method_exists($child, 'toJsonData')))
                {
                    $value[$index] = $child->toJsonData();
                }
                elseif(isDirective($child, 'html'))
                {
                    $value[$index] = $child->data;
                }
            }
            if($key === 'children')
            {
                unset($data['blocks'][$key]);
                $data['children'] = $value;
            }
            else
            {
                $data['blocks'][$key] = $value;
            }
        }

        if(empty($data['blocks'])) unset($data['blocks']);

        if(!empty($this->parent)) $data['parent'] = $this->parent->gid;

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

    public static function getPageCSS() {}

    public static function getPageJS() {}

    protected static function checkPageResources()
    {
        $name = get_called_class();
        if(isset(static::$pageResources[$name])) return;

        static::$pageResources[$name] = true;

        $pageCSS = static::getPageCSS();
        $pageJS  = static::getPageJS();

        if(!empty($pageCSS)) context::css($pageCSS);
        if(!empty($pageJS))  context::js($pageJS);
    }

    public static function wgBlockMap()
    {
        $wgName = get_called_class();
        if(!isset(wg::$wgToBlockMap[$wgName]))
        {
            $wgBlockMap = array();
            if(isset(static::$defineBlocks))
            {
                foreach(static::$defineBlocks as $blockName => $setting)
                {
                    if(!isset($setting['map'])) continue;
                    $map = $setting['map'];
                    if(is_string($map)) $map = explode(',', $map);
                    foreach($map as $name) $wgBlockMap[$name] = $blockName;
                }
            }
            wg::$wgToBlockMap[$wgName] = $wgBlockMap;
        }
        return wg::$wgToBlockMap[$wgName];
    }

    public static function getBlockNameForWg($wg)
    {
        $wgType = ($wg instanceof wg) ? $wg->type() : $wg;
        $wgBlockMap = static::wgBlockMap();
        if(str_starts_with($wgType, 'zin\\')) $wgType = substr($wgType, 4);
        return isset($wgBlockMap[$wgType]) ? $wgBlockMap[$wgType] : NULL;
    }

    public static function nextGid()
    {
        return 'zin' . (++static::$gidSeed);
    }

    protected static function getDefinedProps($name = NULL)
    {
        if($name === NULL) $name = get_called_class();

        if(!isset(wg::$definedPropsMap[$name]) && $name === get_called_class())
        {
            wg::$definedPropsMap[$name] = static::parsePropsDefinition(static::$defineProps);
        }
        return wg::$definedPropsMap[$name];
    }

    /**
     * Parse props definition
     * @param $definition
     * @example
     *
     * $definition = 'name,desc:string,title?:string|element,icon?:string="star"'
     * $definition = array('name', 'desc:string', 'title?:string|element', 'icon?:string="star"');
     * $definition = array('name' => 'mixed', 'desc' => 'string', 'title' => array('type' => 'string|element', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
     */
    private static function parsePropsDefinition($definition)
    {
        $parentClass = get_parent_class(get_called_class());
        $props = $parentClass ? call_user_func("$parentClass::getDefinedProps") : array();

        if((!is_array($definition) && !is_string($definition)) || ($parentClass && $definition === $parentClass::$defineProps))
        {
            if(static::$defaultProps && static::$defaultProps !== $parentClass::$defaultProps)
            {
                foreach($props as $name => $value)
                {
                    if(is_array(static::$defaultProps) && isset(static::$defaultProps[$name]))
                    {
                        $value['default'] = static::$defaultProps[$name];
                        $props[$name]     = $value;
                    }
                }
            }
            return $props;
        }

        if(is_string($definition)) $definition = explode(',', $definition);

        foreach($definition as $name => $value)
        {
            $optional = false;
            $type     = 'mixed';
            $default  = (isset($props[$name]) && isset($props[$name]['default'])) ? $props[$name]['default'] : NULL;

            if(is_int($name) && is_string($value))
            {
                $value = trim($value);
                if(!str_contains($value, ':'))
                {
                    $name  = $value;
                    $value = '';
                }
                else
                {
                    list($name, $value) = explode(':', $value, 2);
                }
                $name = trim($name);
                if($name[strlen($name) - 1] === '?')
                {
                    $name     = substr($name, 0, strlen($name) - 1);
                    $optional = true;
                }
            }

            if(is_array($value))
            {
                $type     = isset($value['type'])    ? $value['type']    : $type;
                $default  = isset($value['default']) ? $value['default'] : $default;
                $optional = isset($value['optional'])? $value['optional']: $optional;
            }
            else if(is_string($value))
            {
                if(!str_contains($value, '='))
                {
                    $type    = $value;
                    $default = NULL;
                }
                else
                {
                    list($type, $default) = explode('=', $value, 2);
                }
                $type = trim($type);

                if(is_string($default)) $default = json_decode(trim($default));
            }

            $props[$name] = array('type' => empty($type) ? 'mixed' : $type, 'default' => $default, 'optional' => $default !== NULL || $optional);
        }

        if(static::$defaultProps && (!$parentClass || static::$defaultProps !== $parentClass::$defaultProps))
        {
            foreach(static::$defaultProps as $name => $value)
            {
                if(!isset($props[$name])) continue;
                $props[$name]['default'] = $value;
            }
        }
        return $props;
    }
}
