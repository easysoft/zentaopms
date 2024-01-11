<?php

declare(strict_types=1);
/**
 * The field class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'core' . DS . 'setting.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';

use \zin\fieldList;

class field extends setting
{
    public ?fieldList $fieldList;

    public ?field $parent;

    public ?string $dataType;

    public mixed $default;

    public function __construct(string|object|array|null $nameOrProps = null, ?fieldList $fieldList = null, ?field $parent = null)
    {
        $this->fieldList = $fieldList;
        $this->parent    = $parent;

        if(is_string($nameOrProps))           $nameOrProps = array('name' => $nameOrProps);
        elseif($nameOrProps instanceof field) $nameOrProps = $nameOrProps->toArray();
        elseif(is_object($nameOrProps))       $nameOrProps = get_object_vars($nameOrProps);

        parent::__construct($nameOrProps);
    }

    public function getName(): string
    {
        return $this->get('name');
    }

    function name(?string $name): field
    {
        return $this->setVal('name', $name);
    }

    function type(?string $type): field
    {
        return $this->setVal('type', $type);
    }

    function id(?string $id): field
    {
        return $this->setVal('id', $id);
    }

    function className(mixed ...$classList): field
    {
        return $this->setClass('className', ...$classList);
    }

    function value(string|array|null $value): field
    {
        return $this->setVal('value', $value);
    }

    function width(string|int|null $width): field
    {
        return $this->setVal('width', $width);
    }

    function required(?bool $required = true): field
    {
        return $this->setVal('required', $required);
    }

    function disabled(?bool $disabled = true): field
    {
        return $this->setVal('disabled', $disabled);
    }

    function readonly(?bool $readonly = true): field
    {
        return $this->setVal('readonly', $readonly);
    }

    function placeholder(?string $placeholder): field
    {
        return $this->setVal('placeholder', $placeholder);
    }

    function foldable(?bool $foldable = true): field
    {
        return $this->setVal('foldable', $foldable);
    }

    function pinned(?bool $pinned = true): field
    {
        return $this->setVal('pinned', $pinned);
    }

    function hidden(?bool $hidden = true): field
    {
        return $this->setVal('hidden', $hidden);
    }

    function strong(?bool $strong = true): field
    {
        return $this->setVal('strong', $strong);
    }

    function label(bool|string|null $label, string|object|array $classOrProps = null): field
    {
        $this->setVal('label', $label);
        if (is_string($classOrProps)) $this->labelClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelProps($classOrProps);
        return $this;
    }

    function labelFor(bool|string|null $for): field
    {
        return $this->setVal('labelFor', $for);
    }

    function labelClass(mixed ...$classList): field
    {
        return $this->setClass('labelClass', ...$classList);
    }

    function labelProps(array|object|null $props): field
    {
        return $this->addToMap('labelProps', $props);
    }

    function labelWidth(int|string|null $width): field
    {
        return $this->setVal('labelWidth', $width);
    }

    function labelHint(?string $hint, string|object|array $classOrProps = null): field
    {
        $this->setVal('labelHint', $hint);
        if (is_string($classOrProps)) $this->labelHintClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelHintProps($classOrProps);
        return $this;
    }

    function labelHintClass(mixed ...$classList): field
    {
        return $this->setClass('labelHintClass', ...$classList);
    }

    function labelHintProps(array|object|null $props): field
    {
        return $this->addToMap('labelHintProps', $props);
    }

    function checkbox(bool|string|null $checkbox = true, array|object|null $props = null): field
    {
        $this->setVal('checkbox', $checkbox);
        if (!is_null($props)) $this->checkboxProps($props);
        return $this;
    }

    function checkboxProps(array|object|null $props): field
    {
        return $this->addToMap('checkboxProps', $props);
    }

    function checked(?bool $checked = true): field
    {
        return $this->setVal('checked', $checked);
    }

    function wrapBefore(?bool $wrapBefore = true): field
    {
        return $this->setVal('wrapBefore', $wrapBefore);
    }

    function wrapAfter(?bool $wrapAfter = true): field
    {
        return $this->setVal('wrapAfter', $wrapAfter);
    }

    function wrap(string $side = 'before', bool $wrap = true): field
    {
        return $this->setVal($side == 'before' ? 'wrapBefore' : 'wrapAfter', $wrap);
    }

    function tip(bool|string|null $tip): field
    {
        return $this->setVal('tip', $tip);
    }

    function tipClass(mixed ...$classList): field
    {
        return $this->setClass('tipClass', ...$classList);
    }

    function tipProps(array|object|null $tipProps): field
    {
        return $this->addToMap('tipProps', $tipProps);
    }

    function multiple(?bool $multiple = true): field
    {
        return $this->setVal('multiple', $multiple);
    }

    function createChild(?string $itemName = null): field
    {
        $item = new field($itemName, null, $this);
        return $item;
    }

    function control(string|array|object|null $control, array|object|null $props = null): field
    {
        if(is_string($control)) $control = array('type' => $control);
        $this->addToMap('control', $control);
        if(!is_null($props)) $this->addToMap('control', $props);
        return $this;
    }

    function controlBegin(?string $itemName): field
    {
        return $this->createChild($itemName);
    }

    function controlEnd(): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "controlBegin($name)" firstly.', E_USER_ERROR);
        }
        return $this->parent->control($this->toArray());
    }

    function items(array|object|false|null $items): field
    {
        if($items === false) return $this->remove('items');
        return $this->addToList('items', $items);
    }

    function item(string|array|object|null $itemOrName, array|object|null $item = null): field
    {
        if(is_null($itemOrName)) return $this;
        if(is_string($itemOrName))
        {
            if(is_null($item)) return $this->removeItem($itemOrName);
            return $this->addToList('items', array($itemOrName => $item));
        }
        return $this->addToList('items', array($itemOrName));
    }

    function itemBegin(?string $itemName = null): field
    {
        return $this->createChild($itemName);
    }

    function itemEnd(): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "itemBegin($name)" firstly.', E_USER_ERROR);
        }
        $this->parent->item($this);
        return $this->parent;
    }

    function removeItem(string $itemName): field
    {
        return $this->removeFromList('items', $itemName);
    }

    function children(mixed ...$children): field
    {
        return $this->addToList('children', $children);
    }

    function setDefault(string|array|null $default): field
    {
        $this->default = $default;
        return $this;
    }

    function setDataType(?string $dataType): field
    {
        $this->dataType = $dataType;
        return $this;
    }

    function moveBefore(string $name): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->parent->moveBefore($this->getName(), $name);
        return $this;
    }

    function moveAfter(string $name): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->parent->moveAfter($this->getName(), $name);
        return $this;
    }

    function detach(): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->parent->remove($this->getName());
        return $this;
    }

    function toArray(): array
    {
        $array = parent::toArray();
        if(isset($array['items']))
        {
            $items = array();
            foreach($array['items'] as $key => $item)
            {
                if($item instanceof \zin\utils\dataset) $item = $item->toArray();
                elseif(is_object($item))     $item = get_object_vars($item);
                $items[$key] = $item;
            }
            $array['items'] = $items;
        }
        return $array;
    }
}
