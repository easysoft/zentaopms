<?php
declare(strict_types=1);
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

require_once __DIR__ . DS . 'props.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'zin.class.php';
require_once __DIR__ . DS . 'context.class.php';
require_once __DIR__ . DS . 'selector.func.php';
require_once __DIR__ . DS . 'dom.class.php';

class wg
{
    /**
     * Define props for the element
     *
     * @var array
     */
    protected static array $defineProps = array();

    protected static array $defaultProps = array();

    protected static array $defineBlocks = array();

    protected static array $wgToBlockMap = array();

    protected static array $definedPropsMap = array();

    protected static array $pageResources = array();

    /**
     * The props of the element
     *
     * @access public
     * @var    props
     */
    public props $props;

    public array $blocks = array();

    public ?wg $parent = null;

    public string $gid;

    public bool $displayed = false;

    public bool $removed = false;

    protected array $renderOptions = array();

    public function __construct(/* string|element|object|array|null ...$args */)
    {
        $this->props = new props();

        $this->gid = 'zin_' . uniqid();
        $this->setDefaultProps(static::getDefaultProps());
        $this->add(func_get_args());
        $this->created();

        zin::renderInGlobal($this);
        static::checkPageResources();

        $this->checkErrors();
    }

    public function __debugInfo(): array
    {
        return $this->toJSON();
    }

    public function isDomElement(): bool
    {
        return false;
    }

    /**
     * Check if the element is match any of the selectors
     * @param  string|array|object $selectors
     */
    public function isMatch(string|array|object $selectors): bool
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

    /**
     * Build dom object
     * @return dom
     */
    public function buildDom(): dom
    {
        $before    = $this->buildBefore();
        $children  = $this->build();
        $after     = $this->buildAfter();
        $options   = $this->renderOptions;
        $selectors = (!empty($options) && isset($options['selector'])) ? $options['selector'] : null;

        return new dom
        (
            $this,
            [$before, $children, $after],
            $selectors,
            (!empty($options) && isset($options['type'])) ? $options['type'] : 'html', // TODO: () may not work in lower php
            (!empty($options) && isset($options['data'])) ? $options['data'] : null,
        );
    }

    /**
     * Mark widget been removed.
     *
     * @param string $selector
     * @access public
     * @return void
     */
    public function remove(string $selector = '')
    {
        if(!empty($selector))
        {
            $list = $this->find($selector);
            foreach($list as $item) $item->remove();
            return;
        }
        $this->removed = true;
    }

    /**
     * Find widgets by selector.
     *
     * @param  string|array|object  $selector
     * @param  string               $blockName
     * @param  bool                 $nested
     * @return array
     * @access public
     */
    public function find(string|array|object $selector, string $blockName = '', bool $nested = true): array
    {
        $selectors = parseWgSelectors($selector);
        $result    = array();
        $blocks    = empty($blockName) ? $this->blocks : array($blockName => isset($this->blocks[$blockName]) ? $this->blocks[$blockName] : array());
        foreach($blocks as $items)
        {
            foreach($items as $item)
            {
                if(!($item instanceof wg)) continue;

                if($item->isMatch($selectors)) $result[] = $item;
                elseif($nested) $result = array_merge($result, $item->find($selectors, '', $nested));
            }
        }
        return $result;
    }

    /**
     * Find children widgets by selector.
     *
     * @param  string $selector
     * @return array
     * @access public
     */
    public function findChildren(string $selector): array
    {
        return $this->find($selector, 'children', false);
    }

    /**
     * Find first widget by selector.
     *
     * @param  string $selector
     * @return wg|null
     * @access public
     */
    public function first(string $selector = ''): wg | null
    {
        return reset($this->find($selector));
    }

    /**
     * Find last widget by selector.
     *
     * @param  string $selector
     * @return wg|null
     * @access public
     */
    public function last(string $selector = ''): wg | null
    {
        return end($this->find($selector));
    }

    /**
     * Render widget to html
     * @return string
     */
    public function render(): string
    {
        if($this->removed) return '';

        $dom    = $this->buildDom();
        $result = $dom->render();

        return is_string($result) ? $result : json_encode($result);
    }

    public function display(array $options = array()): wg
    {
        zin::disableGlobalRender();
        $this->renderOptions = $options;

        $dom     = $this->buildDom();
        $result  = $dom->render();
        $context = context::current();
        $css     = $context->getCSS();
        $js      = $context->getJS();

        global $app, $config;
        $zinDebug = null;
        if($config->debug && (!isAjaxRequest() || isAjaxRequest('zin')))
        {
            $zinDebug = data('zinDebug');
            if(is_array($zinDebug))
            {
                $zinDebug['basePath'] = $app->getBasePath();
                if(isset($app->zinErrors)) $zinDebug['errors'] = $app->zinErrors;
            }
        }

        $rawContent = ob_get_contents();
        if(!is_string($rawContent)) $rawContent = '';
        ob_end_clean();

        if(is_object($result))
        {
            if($zinDebug && isset($result['zinDebug'])) $result['zinDebug'] = $zinDebug;
            $result = json_encode($result);
        }
        elseif(is_array($result))
        {
            foreach($result as $index => $item)
            {
                if($item['name'] === 'zinDebug' && $zinDebug)
                {
                    $result[$index]['data'] = $zinDebug;
                    continue;
                }
                if(!isset($item['type']) || $item['type'] !== 'html') continue;

                $data = $item['data'];
                $data = str_replace('/*{{ZIN_PAGE_CSS}}*/',     $css,        $data);
                $data = str_replace('/*{{ZIN_PAGE_JS}}*/',      $js,         $data);
                $data = str_replace('<!-- {{RAW_CONTENT}} -->', $rawContent, $data);
                $result[$index]['data'] = $data;
            }
            $result = json_encode($result);
        }
        else
        {
            if($zinDebug) $js .= h::createJsVarCode('window.zinDebug', $zinDebug);
            $result = str_replace('/*{{ZIN_PAGE_CSS}}*/', $css, $result);
            $result = str_replace('/*{{ZIN_PAGE_JS}}*/', $js, $result);
            $result = str_replace('<!-- {{RAW_CONTENT}} -->', $rawContent, $result);
        }

        ob_start();
        echo $result;

        $this->displayed = true;
        context::destroy();
        return $this;
    }

    protected function created() {}

    protected function buildBefore(): array
    {
        return $this->block('before');
    }

    protected function buildAfter(): array
    {
        return $this->block('after');
    }

    protected function build(): array|wg|directive
    {
        if($this->removed) return array();

        return $this->children();
    }

    public function buildEvents(): ?string
    {
        $events = $this->props->events();
        if(empty($events)) return null;

        $id   = $this->id();
        $code = array($this->shortType() === 'html' ? 'const ele = document;' : 'const ele = document.getElementById("' . (empty($id) ? $this->gid : $id) . '");if(!ele)return;const $ele = $(ele); const events = new Set(($ele.attr("data-zin-events") || "").split(" ").filter(Boolean));');
        foreach($events as $event => $bindingList)
        {
            $code[]   = "\$ele.on('$event.on.zin', function(e){";
            foreach($bindingList as $binding)
            {
                if(is_string($binding)) $binding = (object)array('handler' => $binding);
                $selector = isset($binding->selector) ? $binding->selector : null;
                $handler  = isset($binding->handler) ? trim($binding->handler) : '';
                $stop     = isset($binding->stop) ? $binding->stop : null;
                $prevent  = isset($binding->prevent) ? $binding->prevent : null;
                $self     = isset($binding->self) ? $binding->self : null;

                $code[]   = '(function(){';
                if($selector) $code[] = "const target = e.target.closest('$selector');if(!target) return;";
                else          $code[] = "const target = ele;";
                if($self)     $code[] = "if(ele !== e.target) return;";
                if($stop)     $code[] = "e.stopPropagation();";
                if($prevent)  $code[] = "e.preventDefault();";

                if(preg_match('/^[$A-Z_][0-9A-Z_$\[\]."\']*$/i', $handler)) $code[] = "($handler).call(target,e);";
                else $code[] = $handler;

                $code[] = '})();';
            }
            $code[] = "});events.add('$event');";
        }
        $code[] = '$ele.attr("data-zin-events", Array.from(events).join(" "));';
        return h::createJsScopeCode($code);
    }


    protected function onAddBlock(array|string|wg|directive $child, string $name)
    {
        return $child;
    }

    protected function onAddChild(array|string|wg|directive $child)
    {
        return $child;
    }

    protected function onSetProp(array|string $prop, mixed $value)
    {
        if($prop === 'id' && $value === '$GID') $value = $this->gid;
        if($prop[0] === '@')
        {
            $this->setDefaultProps(array('id' => $this->gid));
            context::current()->addWgWithEvents($this);
        }
        $this->props->set($prop, $value);
    }

    protected function onGetProp(string $prop, mixed $defaultValue): mixed
    {
        return $this->props->get($prop, $defaultValue);
    }

    public function add($item, string $blockName = 'children')
    {
        if($item === null || is_bool($item)) return $this;

        if(is_array($item))
        {
            foreach($item as $child) $this->add($child, $blockName);
            return $this;
        }

        zin::disableGlobalRender();

        if($item instanceof wg)    $this->addToBlock($blockName, $item);
        elseif(is_string($item))   $this->addToBlock($blockName, htmlspecialchars($item, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false));
        elseif(isDirective($item)) $this->directive($item, $blockName);
        else                       $this->addToBlock($blockName, htmlspecialchars(strval($item), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false));

        zin::enableGlobalRender();

        return $this;
    }

    public function addToBlock(array|string $name, array|string|null|wg|directive $child = null)
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

        if($name === 'children' && $child instanceof wg)
        {
            $blockName = static::getBlockNameForWg($child);
            if($blockName !== null) $name = $blockName;
        }

        $result = $name === 'children' ? $this->onAddChild($child) : $this->onAddBlock($child, $name);

        if($result === false) return;
        if($result !== null && $result !== true) $child = $result;

        if(isset($this->blocks[$name])) $this->blocks[$name][] = $child;
        else $this->blocks[$name] = array($child);
    }

    public function children(): array
    {
        return $this->block('children');
    }

    public function block(string $name): array
    {
        $list = array();
        if(isset($this->blocks[$name]))
        {
            $items = $this->blocks[$name];
            foreach($items as $item)
            {
                $isWg = $item instanceof wg && $item->shortType() === 'wg';
                $item = $isWg ? $item->children() : $item;
                if(is_array($item)) $list = array_merge($list, $item);
                else                 $list[] = $item;
            }
        }
        return $list;
    }

    public function hasBlock(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Apply directive
     */
    public function directive(directive &$directive, array|string $blockName)
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
        }
    }

    public function prop(array|string $name, mixed $defaultValue = null): mixed
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
     * @param props|array|string   $prop        - Property name or properties list
     * @param mixed          $value       - Property value
     */
    public function setProp(props|array|string $prop, mixed $value = null)
    {
        if($prop instanceof props) $prop = $prop->toJSON();

        if(is_array($prop))
        {
            foreach($prop as $name => $value) $this->setProp($name, $value);
            return $this;
        }

        if(!is_string($prop) || empty($prop)) return $this;

        if($prop[0] === '#')
        {
            $this->add($value, substr($prop, 1));
            return $this;
        }

        $this->onSetProp($prop, $value);
        return $this;
    }

    public function hasProp(): bool
    {
        $names = func_get_args();
        if(empty($names)) return false;
        foreach($names as $name)
        {
            if(!$this->props->has($name)) return false;
        }
        return true;
    }

    public function setDefaultProps(array $props)
    {
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->has($name)) continue;
            $this->setProp($name, $value);
        }
    }

    public function getRestProps(): array
    {
        return $this->props->skip(array_keys(static::definedPropsList()));
    }

    public function getDefinedProps(): array
    {
        return $this->props->pick(array_keys(static::definedPropsList()));
    }

    public function type(): string
    {
        return get_called_class();
    }

    public function shortType(): string
    {
        $type = $this->type();
        $pos = strrpos($type, '\\');
        return $pos === false ? $type : substr($type, $pos + 1);
    }

    public function id(): ?string
    {
        return $this->prop('id');
    }

    public function toJSON(): array
    {
        $data = array();
        $data['gid']     = $this->gid;
        $data['id']      = $this->id();
        $data['removed'] = $this->removed;
        $data['props']   = $this->props->toJSON();

        $data['type'] = $this->type();
        if(str_starts_with($data['type'], 'zin\\')) $data['type'] = substr($data['type'], 4);

        $data['blocks'] = array();
        foreach($this->blocks as $key => $value)
        {
            foreach($value as $index => $child)
            {
                if($child instanceof wg || (is_object($child) && method_exists($child, 'toJSON')))
                {
                    $value[$index] = $child->toJSON();
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

    /**
     * Check errors in debug mode.
     *
     * @access protected
     * @return void
     */
    protected function checkErrors()
    {
        global $config;
        if(!isset($config->debug) || !$config->debug) return;

        $definedProps = static::definedPropsList();
        foreach($definedProps as $name => $definition)
        {
            if($this->hasProp($name)) continue;
            if(isset($definition['default']) && $definition['default'] !== null) continue;
            if(isset($definition['optional']) && $definition['optional']) continue;

            trigger_error("[ZIN] The property \"$name: {$definition['type']}\" of widget \"{$this->type()}#$this->gid\" is required.", E_USER_ERROR);
        }

        $wgErrors = $this->onCheckErrors();
        if(empty($wgErrors)) return;

        foreach($wgErrors as $error)
        {
            if(is_array($error)) trigger_error("[ZIN] $error[0]", count($error) > 1 ? $error[1] : E_USER_WARNING);
            else trigger_error("[ZIN] $error", E_USER_ERROR);
        }
    }

    /**
     * The lifecycle method for checking errors in debug mode.
     *
     * @access protected
     * @return array|null
     */
    protected function onCheckErrors(): array|null
    {
        return null;
    }

    public static function getPageCSS(): string|false
    {
        return false; // No css
    }

    public static function getPageJS(): string|false
    {
        return false; // No js
    }

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

    public static function wgBlockMap(): array
    {
        $wgName = get_called_class();
        if(!isset(wg::$wgToBlockMap[$wgName]))
        {
            $wgBlockMap = array();
            if(!empty(static::$defineBlocks))
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

    public static function getBlockNameForWg(wg|string $wg): ?string
    {
        $wgType = ($wg instanceof wg) ? $wg->type() : $wg;
        $wgBlockMap = static::wgBlockMap();
        if(str_starts_with($wgType, 'zin\\')) $wgType = substr($wgType, 4);
        return isset($wgBlockMap[$wgType]) ? $wgBlockMap[$wgType] : null;
    }

    protected static function definedPropsList(?string $wgName = null): array
    {
        if($wgName === null) $wgName = get_called_class();

        if(!isset(wg::$definedPropsMap[$wgName]) && $wgName === get_called_class())
        {
            wg::$definedPropsMap[$wgName] = static::parsePropsDefinition(static::$defineProps);
        }
        return wg::$definedPropsMap[$wgName];
    }

    protected static function getDefaultProps(?string $wgName = null): array
    {
        $defaultProps = array();
        foreach(static::definedPropsList($wgName) as $name => $definition)
        {
            if(!isset($definition['default'])) continue;
            $defaultProps[$name] = $definition['default'];
        }
        return $defaultProps;
    }

    /**
     * Parse props definition
     * @param $definition
     * @example
     *
     * $definition = array('name', 'desc:string', 'title?:string|element', 'icon?:string="star"');
     * $definition = array('name' => 'mixed', 'desc' => 'string', 'title' => array('type' => 'string|element', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
     */
    private static function parsePropsDefinition(array $definition): array
    {
        $parentClass = get_parent_class(get_called_class());
        /**
         * @var array
         */
        $props = $parentClass ? call_user_func("$parentClass::definedPropsList") : array();

        if($parentClass !== false && $definition === $parentClass::$defineProps)
        {
            if(!empty(static::$defaultProps) && static::$defaultProps !== $parentClass::$defaultProps)
            {
                foreach($props as $name => $value)
                {
                    if(isset(static::$defaultProps[$name]))
                    {
                        $value['default'] = static::$defaultProps[$name];
                        $props[$name]     = $value;
                    }
                }
            }
            return $props;
        }

        foreach($definition as $name => $value)
        {
            $optional = false;
            $type     = 'mixed';
            $default  = (isset($props[$name]) && isset($props[$name]['default'])) ? $props[$name]['default'] : null;

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
                    $default = null;
                }
                else
                {
                    list($type, $default) = explode('=', $value, 2);
                }
                $type = trim($type);

                if(is_string($default)) $default = json_decode(trim($default));
            }

            $props[$name] = array('type' => empty($type) ? 'mixed' : $type, 'default' => $default, 'optional' => $default !== null || $optional);
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
