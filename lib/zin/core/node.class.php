<?php
declare(strict_types=1);
/**
 * The base node class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'helper.func.php';
require_once __DIR__ . DS . 'selector.func.php';
require_once __DIR__ . DS . 'props.class.php';

/**
 * The base node class.
 */
class node implements \JsonSerializable
{
    /**
     * Define properties
     *
     * @access public
     * @var    array
     */
    protected static array $defineProps = array();

    /**
     * Default properties
     *
     * @access public
     * @var    array
     */
    protected static array $defaultProps = array();

    protected static array $defineBlocks = array();

    public string $gid;

    public ?node $parent = null;

    public ?node $rootNode = null;

    public props $props;

    public array $blocks = array();

    public bool $removed = false;

    public ?array $replacedWith = null;

    public ?stdClass $buildData = null;

    public array $eventBindings = array();

    public function __construct(mixed ...$args)
    {
        $this->gid   = 'zin_' . uniqid();
        $this->props = new props();

        disableGlobalRender();

        $this->setDefaultProps(static::getDefaultProps());
        $this->add($args);
        $this->created();

        enableGlobalRender();
        renderInGlobal($this);
    }

    public function __debugInfo(): array
    {
        return (array)$this->toJSON();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function fullType(): string
    {
        return get_called_class();
    }

    public function type(): string
    {
        $type = $this->fullType();
        if(str_contains($type, '\\'))
        {
            $type = substr($type, strrpos($type, '\\') + 1);
        }
        return $type;
    }

    public function id(): ?string
    {
        return $this->props->get('id');
    }

    public function displayID(): string
    {
        $displayID = $this->fullType() . '~' . $this->gid;

        $id = $this->id();
        if(!empty($id)) $displayID .= "#$id";

        return $displayID;
    }

    /**
     * Check if the element is match any of the selectors
     * @param  string|array|object $selectors
     */
    public function is(string|array|object $selectors): bool
    {
        $list = parseSelectors($selectors);
        foreach($list as $selector)
        {
            if($this->isMatch($selector)) return true;
        }
        return false;
    }

    public function isMatch(object $selector): bool
    {
        if(!empty($selector->id)      && $this->id() !== $selector->id)               return false;
        if(!empty($selector->tag)     && $this->type() !== $selector->tag)            return false;
        if(!empty($selector->class)   && !$this->props->class->has($selector->class)) return false;
        if(!empty($selector->parents) && !$this->hasParents(...$selector->parents))   return false;
        return true;
    }

    public function hasParents(object|string ...$parentSelectors): bool
    {
        $parent = $this->parent;
        if(!$parent) return false;

        foreach($parentSelectors as $selector)
        {
            $parent = $parent->closest($selector);
            if(!$parent) return false;
        }

        return true;
    }

    public function off(string $event)
    {
        unset($this->eventBindings[$event]);
        $this->props->remove("@$event");
    }

    public function closest(string|array|object $selectors): ?node
    {
        $list = parseSelectors($selectors, true);
        $node = $this;
        while($node)
        {
            if($node->is($list)) return $node;
            $node = $node->parent;
        }
        return null;
    }

    public function find(string|array|object $selectors, bool $first = false, bool $reverse = false, bool $prebuild = true): array
    {
        if($prebuild) $this->prebuild();
        return findInNode(parseSelectors($selectors, true), $this, $first, $reverse);
    }

    public function findFirst(string|array|object $selectors): ?node
    {
        $results = $this->find($selectors, true);
        return empty($results) ? null : $results[0];
    }

    public function findLast(string|array|object $selectors): ?node
    {
        $results = $this->find($selectors, true, true);
        return empty($results) ? null : $results[0];
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

    public function setDefaultProps(string|array $props, mixed $value = null)
    {
        if(is_string($props)) $props = array($props => $value);
        if(!is_array($props) || empty($props)) return;

        foreach($props as $name => $value)
        {
            if($this->props->isset($name)) continue;
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

    public function add(mixed $item, string $blockName = 'children', bool $prepend = false)
    {
        if($item === null || is_bool($item)) return;

        if(is_array($item))
        {
            foreach($item as $child) $this->add($child, $blockName, $prepend);
            return;
        }

        if(isDirective($item)) $this->directive($item, $blockName);
        else $this->addToBlock($blockName, $item, $prepend);
    }

    public function addToBlock(string $name, mixed $child, bool $prepend = false)
    {
        if($child === null || is_bool($child)) return;

        if(is_array($child))
        {
            foreach($child as $blockChild)
            {
                $this->addToBlock($name, $blockChild, $prepend);
            }
            return;
        }

        if($child instanceof node) $child->parent = $this;

        if($child instanceof node)
        {
            if(isset($child->parent)) skipRenderInGlobal($child);
            $child->parent = $this;
        }

        if($name === 'children' && $child instanceof node)
        {
            $blockName = static::getNameFromBlockMap($child->fullType());
            if($blockName !== null) $name = $blockName;
        }
        elseif(is_string($child))
        {
            /* Encode html special chars. */
            $child = htmlspecialchars(strval($child), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, null, false);
        }

        $result = $name === 'children' ? $this->onAddChild($child) : $this->onAddBlock($child, $name);
        if($result === false) return;
        if($result !== null && $result !== true) $child = $result;

        if(isset($this->blocks[$name]))
        {
            if($prepend) array_unshift($this->blocks[$name], $child);
            else         $this->blocks[$name][] = $child;
        }
        else
        {
            $this->blocks[$name]   = array($child);
        }
    }

    public function directive(iDirective $directive, string $blockName = 'children')
    {
        if(!isset($directive->parent) || !$directive->parent) $directive->parent = $this;
        $directive->apply($this, $blockName);
    }

    public function addChild(mixed $child)
    {
        return $this->addToBlock('children', $child);
    }

    public function remove()
    {
        $this->removed = true;
    }

    public function empty(?string $blockName = null)
    {
        if($blockName) unset($this->blocks[$blockName]);
        else           $this->blocks = array();

        $this->removeBuildData('all');
    }

    public function bindEvent(string $event, object|array $info)
    {
        if($info instanceof jsCallback)
        {
            $this->props->bindEvent($event, $info->toJS());
            $this->removeBuildData();
            return;
        }

        if(is_array($info)) $info = (object)$info;
        if(!isset($this->eventBindings[$event])) $this->eventBindings[$event] = array();
        $this->eventBindings[$event][] = $info;

        if(!$this->hasProp('id')) $this->setProp('id', $this->gid);
    }

    public function buildEvents(): ?string
    {
        $events = $this->eventBindings;
        if(empty($events)) return null;

        $id   = $this->id();
        $code = array($this->type() === 'html' ? 'const ele = document;' : 'const ele = document.getElementById("' . (empty($id) ? $this->gid : $id) . '");if(!ele)return;const $ele = $(ele); const events = new Set(($ele.attr("data-zin-events") || "").split(" ").filter(Boolean));');
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
        return js::scope($code);
    }

    public function render(): string
    {
        if($this->removed) return '';

        $data = new stdClass();
        $data->html = renderToHtml(...$this->buildAll());

        context()->handleRenderNode($data, $this);

        return $data->html;
    }

    public function renderInner(): string
    {
        if($this->removed) return '';

        return renderToHtml(...$this->children());
    }

    public function removeBuildData(string $type = 'children')
    {
        if(!$this->buildData) return;

        if($type === 'all')
        {
            $this->buildData = new stdClass();
        }
        elseif($type === 'before')
        {
            unset($this->buildData->before);
        }
        elseif($type === 'after')
        {
            unset($this->buildData->after);
        }
        else
        {
            unset($this->buildData->content);
            unset($this->buildData->children);
        }
   }

    public function prebuild(bool $force = false): stdClass
    {
        $firstBuild = ($this->buildData === null || $force);
        if($firstBuild)
        {
            $context = context();
            $context->handleBeforeBuildNode($this);

            $data = new stdClass();
            $data->before   = prebuild($this->buildBefore(), $this);
            $data->children = prebuild($this->children(), $this);
            $data->content  = prebuild($this->buildContents(), $this);
            $data->after    = prebuild($this->buildAfter(), $this);

            $this->buildData = $data;

            $context->handleBuildNode($data, $this);
        }
        else
        {
            if(!isset($this->buildData->before))   $this->buildData->before   = prebuild($this->buildBefore(), $this);
            if(!isset($this->buildData->children)) $this->buildData->children = prebuild($this->children(), $this);
            if(!isset($this->buildData->content))  $this->buildData->content  = prebuild($this->buildContents(), $this);
            if(!isset($this->buildData->after))    $this->buildData->after    = prebuild($this->buildAfter(), $this);
        }

        return $this->buildData;
    }

    public function buildContents(): array
    {
        $content = $this->build();
        if(is_null($content) || is_bool($content)) $content = array();
        elseif(!is_array($content))                $content = array($content);
        return $content;
    }

    public function buildAll(): array
    {
        if($this->replacedWith !== null) return $this->replacedWith;

        $data = $this->prebuild();
        return array_merge($data->before, $data->content, $data->after);
    }

    public function replaceWith(mixed ...$args)
    {
        $this->replacedWith = $args;
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
                if(is_array($item)) $list   = array_merge($list, $item);
                else                $list[] = $item;
            }
        }
        return $list;
    }

    public function hasBlock(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Convert to JSON object.
     *
     * @access public
     * @return object
     */
    public function toJSON(): object
    {
        $json = new stdClass();
        $json->gid   = $this->gid;
        $json->type  = $this->type();
        $json->props = $this->props->toJSON();

        $json->blocks = array();
        foreach($this->blocks as $key => $block)
        {
            foreach($block as $index => $child)
            {
                if($child instanceof node || (is_object($child) && method_exists($child, 'toJSON')))
                {
                    $block[$index] = $child->toJSON();
                }
            }

            if($key === 'children')
            {
                $json->$key = $block;
                unset($json->blocks[$key]);
            }
            else
            {
                $json->blocks[$key] = $block;
            }
        }

        if(!$json->blocks) unset($json->blocks);

        $id = $this->id();
        if($id !== null) $json->id = $id;

        $parent = $this->parent;
        if($parent !== null) $json->parent = $parent->displayID();

        if($this->removed) $json->removed = true;

        return $json;
    }

    /**
     * Serialized to JSON string.
     *
     * @access public
     * @return string
     */
    public function jsonSerialize(): string
    {
        return json_encode($this->toJSON());
    }

    /**
     * Trigger error in debug mode.
     *
     * @access public
     * @param  string $message
     * @param  int    $level
     * @return void
     */
    public function triggerError(string $message, int $level = E_USER_ERROR)
    {
        triggerError("{$this->displayID()}: $message", $level);
    }

    protected function build()
    {
        return $this->children();
    }

    protected function buildBefore(): array
    {
        return $this->block('before');
    }

    protected function buildAfter(): array
    {
        return $this->block('after');
    }

    protected function created()
    {
    }

    protected function onAddChild(mixed $child)
    {
        $this->removeBuildData();
        return $child;
    }

    protected function onAddBlock(mixed $child, string $name)
    {
        $this->removeBuildData($name);
        return $child;
    }

    protected function onSetProp(array|string $prop, mixed $value)
    {
        if($prop === 'id' && $value === '$GID') $value = $this->gid;

        $this->props->set($prop, $value);
        $this->removeBuildData();
    }

    protected function onGetProp(string $prop, mixed $defaultValue): mixed
    {
        return $this->props->get($prop, $defaultValue);
    }

    /**
     * Check errors in debug mode.
     *
     * @access protected
     * @return void
     */
    protected function checkErrors()
    {
        if(!isDebug()) return;

        $definedProps = static::definedPropsList();
        foreach($definedProps as $name => $definition)
        {
            if($this->hasProp($name)) continue;
            if(isset($definition['default']) && $definition['default'] !== null) continue;
            if(isset($definition['optional']) && $definition['optional']) continue;

            $this->triggerError("The value of property \"$name: {$definition['type']}\" is required.");
        }

        $wgErrors = $this->onCheckErrors();
        if(empty($wgErrors)) return;

        foreach($wgErrors as $error)
        {
            if(is_array($error)) $this->triggerError(...$error);
            else $this->triggerError($error);
        }
    }

    /**
     * The lifecycle method for checking errors in debug mode.
     *
     * @access protected
     * @return array|null
     */
    protected function onCheckErrors(): ?array
    {
        return null;
    }

    protected static array $definedPropsMap = array();

    protected static array $blockMap = array();

    public static function getBlockMap(): array
    {
        $type = get_called_class();
        if(!isset(node::$blockMap[$type]))
        {
            $blockMap = array();
            if(is_array(static::$defineBlocks))
            {
                foreach(static::$defineBlocks as $blockName => $setting)
                {
                    if(!isset($setting['map'])) continue;
                    $map = $setting['map'];
                    if(is_string($map)) $map = explode(',', $map);
                    foreach($map as $name) $blockMap[$name] = $blockName;
                }
            }
            node::$blockMap[$type] = $blockMap;
        }
        return node::$blockMap[$type];
    }

    public static function getNameFromBlockMap(string $type): ?string
    {
        $blockMap = static::getBlockMap();
        if(str_starts_with($type, 'zin\\')) $type = substr($type, 4);
        return isset($blockMap[$type]) ? $blockMap[$type] : null;
    }

    public static function definedPropsList(?string $type = null): array
    {
        if($type === null) $type = get_called_class();

        if(!isset(node::$definedPropsMap[$type]) && $type === get_called_class())
        {
            node::$definedPropsMap[$type] = static::parsePropsDefinition();
        }
        return node::$definedPropsMap[$type];
    }

    public static function getDefaultProps(?string $type = null): array
    {
        $type             = $type ? $type : get_called_class();
        $defaultProps     = array();
        $definedPropsList = static::definedPropsList($type);

        foreach($definedPropsList as $name => $definition)
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
     * $definition = array('name', 'desc:string', 'title?:string|array', 'icon?:string="star"');
     * $definition = array('name' => 'mixed', 'desc' => '?string', 'title' => array('type' => 'string|array', 'optional' => true), 'icon' => array('type' => 'string', 'default' => 'star', 'optional' => true))))
     */
    protected static function parsePropsDefinition(): array
    {
        $parentClass  = get_parent_class(get_called_class());
        $parentProps  = array();
        $defaultProps = static::$defaultProps;
        $definition   = static::$defineProps;

        if($parentClass)
        {
            if($definition === $parentClass::$defineProps)    $definition = array();
            if($defaultProps === $parentClass::$defaultProps) $defaultProps = array();

            $parentProps = call_user_func("$parentClass::definedPropsList", $parentClass);
        }

        return parsePropsMap($definition, $parentProps, $defaultProps);
    }
}

function findInNode(array $selectors, node|array $list, bool $first = false, bool $reverse = false, bool $onlyContent = true): array
{
    if($list instanceof node)
    {
        $data = $list->buildData;
        if(!$data || !isset($data->content) || empty($data->content)) return array();

        $list = $data->content;
        if(!$onlyContent) $list = array_merge($data->before, $list, $data->after);
    }
    if($reverse) $list = array_reverse($list);
    $result = array();
    foreach($list as $child)
    {
        if(is_array($child))
        {
            $childList = findInNode($selectors, $child, $first, $reverse, $onlyContent);
            if(!empty($childList))
            {
                if($first) return $childList;
                $result = array_merge($result, $childList);
            }
            continue;
        }

        if(!($child instanceof node)) continue;

        if($child->is($selectors) && $child->type() !== 'item')
        {
            if($child->parent && ($child->parent->removed || ($child->parent instanceof wg && $child->parent->type() !== 'item' && $child->parent->is($selectors)))) continue;
            $result[$child->gid] = $child;
            if($first) return $result;
        }

        $childList = findInNode($selectors, $child, $first, $reverse, $onlyContent);
        if(!empty($childList))
        {
            if($first) return $childList;
            $result = array_merge($result, $childList);
        }
    }
    return $result;
}

function renderToHtml(mixed ...$items): string
{
    $html = '';

    foreach($items as $item)
    {
        if(is_array($item))
        {
            $html .= renderToHtml(...$item);
            continue;
        }
        if($item instanceof node || (is_object($item) && method_exists($item, 'render')))
        {
            $html .= $item->render();
            continue;
        }
        if(is_object($item) && isset($item->html))
        {
            $html .= $item->html;
            continue;
        }
        if(!is_string($item)) $item = strval($item);
        $html .= strval($item);
    }

    return $html;
}

function prebuild(array $items, ?node $parent = null)
{
    foreach($items as $index => $item)
    {
        if(is_array($item))
        {
            $items[$index] = prebuild($item, $parent);
            continue;
        }
        if(!($item instanceof node)) continue;
        if($parent) $item->parent = $parent;
        $item->prebuild();
    }
    return $items;
}

/**
 * Parse the props definition.
 *
 * @param array $definition    - The props definition.
 * @param array $parentProps   - The parent props.
 * @param array $defaultValues - The default values.
 * @return array
 */
function parsePropsMap(array $definition, array $parentProps = array(), array $defaultValues = array())
{
    $props = $parentProps;
    foreach($parentProps as $parentProp)
    {
        $name = $parentProp['name'];
        if(isset($defaultValues[$name])) $parentProp['default'] = $defaultValues[$name];
        $props[$name] = $parentProp;
    }

    foreach($definition as $name => $value)
    {
        $prop = parseProp($value, is_string($name) ? $name : null);
        $name = $prop['name'];

        if(isset($defaultValues[$name]))
        {
            $prop['default'] = $defaultValues[$name];
        }
        elseif(!isset($prop['default']) && isset($parentProps[$name]['default']) && $parentProps[$name]['default'])
        {
            $prop['default'] = $parentProps[$name]['default'];
        }

        $props[$name] = $prop;
    }

    return $props;
}

/**
 * Parse the prop definition.
 *
 * @param string|array $definition - The prop definition.
 * @param string|null  $name       - The prop name.
 * @return array
 */
function parseProp(string|array $definition, ?string $name = null)
{
    $optional = false;
    $type     = 'mixed';
    $prop     = array();

    if(is_string($definition)) $definition = trim($definition);

    /* Parse definition like `'name?: type1|type2="default"'` . */
    if(!$name && is_string($definition))
    {
        if(str_contains($definition, ':'))
        {
            list($name, $definition) = explode(':', $definition, 2);
        }
        else
        {
            $name       = $definition;
            $definition = '';
        }
        $name = trim($name);
        if(str_ends_with($name, '?'))
        {
            $name     = substr($name, 0, strlen($name) - 1);
            $optional = true;
        }
    }

    /* Parse definition like `'name' => '?type1|type2="default"'` . */
    if(is_array($definition))
    {
        if(isset($definition['type']))     $type = $definition['type'];
        if(isset($definition['default']))  $prop['default'] = $definition['default'];
        if(isset($definition['optional'])) $optional = $definition['optional'];
    }
    else if(is_string($definition))
    {
        if(str_contains($definition, '='))
        {
            list($type, $default) = explode('=', $definition, 2);
            if(strlen($default)) $prop['default'] = json_decode(trim($default));
        }
        else
        {
            $type = $definition;
        }
    }

    $type = trim($type);
    if(str_starts_with($type, '?'))
    {
        $type     = substr($type, 1);
        $optional = true;
    }

    $typeList = explode('|', $type);
    if(in_array('null', $typeList) || in_array('mixed', $typeList))
    {
        $optional = true;
    }
    elseif($optional)
    {
        array_unshift($typeList, 'null');
    }

    $prop['name']     = $name;
    $prop['type']     = implode('|', $typeList);
    $prop['optional'] = $optional || (isset($prop['default']) && $prop['default'] !== null);
    return $prop;
}
