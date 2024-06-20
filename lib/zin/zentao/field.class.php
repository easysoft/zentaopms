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

    public function getName(): ?string
    {
        return $this->get('name');
    }

    public function name(?string $name): field
    {
        return $this->setVal('name', $name);
    }

    public function type(?string $type): field
    {
        return $this->setVal('type', $type);
    }

    public function group(?string $group): field
    {
        return $this->setVal('group', $group);
    }

    public function id(?string $id): field
    {
        return $this->setVal('id', $id);
    }

    public function className(mixed ...$classList): field
    {
        return $this->setClass('className', ...$classList);
    }

    public function value(string|array|int|null $value): field
    {
        return $this->setVal('value', $value);
    }

    public function width(string|int|null $width): field
    {
        return $this->setVal('width', $width);
    }

    public function required(?bool $required = true): field
    {
        return $this->setVal('required', $required);
    }

    public function disabled(?bool $disabled = true): field
    {
        return $this->setVal('disabled', $disabled);
    }

    public function readonly(?bool $readonly = true): field
    {
        return $this->setVal('readonly', $readonly);
    }

    public function placeholder(?string $placeholder): field
    {
        return $this->setVal('placeholder', $placeholder);
    }

    public function foldable(?bool $foldable = true): field
    {
        return $this->setVal('foldable', $foldable);
    }

    public function pinned(?bool $pinned = true): field
    {
        return $this->setVal('pinned', $pinned);
    }

    public function hidden(?bool $hidden = true): field
    {
        return $this->setVal('hidden', $hidden);
    }

    public function strong(?bool $strong = true): field
    {
        return $this->setVal('strong', $strong);
    }

    public function label(bool|string|null $label, string|object|array $classOrProps = null): field
    {
        $this->setVal('label', $label);
        if (is_string($classOrProps)) $this->labelClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelProps($classOrProps);
        return $this;
    }

    public function labelFor(bool|string|null $for): field
    {
        return $this->setVal('labelFor', $for);
    }

    public function labelClass(mixed ...$classList): field
    {
        return $this->setClass('labelClass', ...$classList);
    }

    public function labelProps(array|object|null $props): field
    {
        return $this->addToMap('labelProps', $props);
    }

    public function labelWidth(int|string|null $width): field
    {
        return $this->setVal('labelWidth', $width);
    }

    public function labelHint(?string $hint, string|object|array $classOrProps = null): field
    {
        $this->setVal('labelHint', $hint);
        if (is_string($classOrProps)) $this->labelHintClass($classOrProps);
        else if (!is_null($classOrProps)) $this->labelHintProps($classOrProps);
        return $this;
    }

    public function labelHintClass(mixed ...$classList): field
    {
        return $this->setClass('labelHintClass', ...$classList);
    }

    public function labelHintProps(array|object|null $props): field
    {
        return $this->addToMap('labelHintProps', $props);
    }

    public function labelHintIcon(array|object|string|null $icon): field
    {
        return $this->setVal('labelHintIcon', $icon);
    }

    public function labelActions(array|false|null $actions, bool $reset = false, ?string $key = null): field
    {
        if($actions === false)  return $this->remove('actions');
        if($reset)              return $this->setVal('actions', $actions);
        if(is_array($actions))  return $this->mergeToList('actions', $actions, $key);
        return $this;
    }

    public function labelAction(mixed $action, ?string $key = null): field
    {
        return $this->addToList('labelActions', $action, $key);
    }

    public function labelActionsClass(mixed ...$classList): field
    {
        return $this->setClass('labelActionsClass', ...$classList);
    }

    public function labelActionsProps(array|object|null $props): field
    {
        return $this->addToMap('labelActionsProps', $props);
    }

    public function checkbox(bool|array|null $checkbox = true): field
    {
        return $this->setVal('checkbox', $checkbox);
    }

    public function wrapBefore(?bool $wrapBefore = true): field
    {
        return $this->setVal('wrapBefore', $wrapBefore);
    }

    public function wrapAfter(?bool $wrapAfter = true): field
    {
        return $this->setVal('wrapAfter', $wrapAfter);
    }

    public function wrap(string $side = 'before', bool $wrap = true): field
    {
        return $this->setVal($side == 'before' ? 'wrapBefore' : 'wrapAfter', $wrap);
    }

    public function text(bool|string|null $text): field
    {
        return $this->setVal('text', $text);
    }

    public function tip(bool|string|null $tip): field
    {
        return $this->setVal('tip', $tip);
    }

    public function tipClass(mixed ...$classList): field
    {
        return $this->setClass('tipClass', ...$classList);
    }

    public function tipProps(array|object|null $tipProps): field
    {
        return $this->addToMap('tipProps', $tipProps);
    }

    public function multiple(?bool $multiple = true): field
    {
        return $this->setVal('multiple', $multiple);
    }

    public function data(array|object|string|null $nameOrData, mixed $value = null): field
    {
        if(is_string($nameOrData)) $nameOrData = array($nameOrData => $value);
        return $this->addToMap('data', $nameOrData);
    }

    public function createChild(?string $itemName = null): field
    {
        $item = new field($itemName, null, $this);
        return $item;
    }

    public function control(string|array|object|false|null $control, array|object|null $props = null): field
    {
        if($control === false) return $this->remove('control');

        if(is_object($props)) $props = get_object_vars($props);
        if(is_string($control) && is_array($props)) $control = array('control' => $control) + $props;
        elseif($control instanceof node)            $control = node($control);

        return $this->setVal('control', $control);
    }

    public function controlBegin(?string $itemName): field
    {
        return $this->createChild($itemName);
    }

    public function controlEnd(): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "controlBegin($name)" firstly.', E_USER_ERROR);
        }

        $control = $this->toArray();
        if(!isset($control['control'])) $control['control'] = $this->getName();
        unset($control['name']);

        return $this->parent->control($control);
    }

    /**
     * Set items.
     *
     * @access public
     * @param  array|false|null $items  - Items.
     * @return field
     */
    public function items(array|false|null $items): field
    {
        if($items === false) return $this->remove('items');
        return $this->setVal('items', $items);
    }

    /**
     * Add item.
     */
    public function item(mixed $item, ?string $key = null): field
    {
        return $this->addToList('items', $item, $key);
    }

    public function itemBegin(?string $itemName = null): field
    {
        return $this->createChild($itemName);
    }

    public function itemEnd(): field
    {
        if(is_null($this->parent))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no parent, maybe you should call "itemBegin($name)" firstly.', E_USER_ERROR);
        }
        $this->parent->item($this);
        return $this->parent;
    }

    public function children(mixed ...$children): field
    {
        return $this->mergeToList('children', $children);
    }

    public function setDefault(string|array|null $default): field
    {
        $this->default = $default;
        return $this;
    }

    public function setDataType(?string $dataType): field
    {
        $this->dataType = $dataType;
        return $this;
    }

    public function moveBefore(string $name): field
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveBefore($this->getName(), $name);
        return $this;
    }

    public function moveAfter(string $name): field
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveAfter($this->getName(), $name);
        return $this;
    }

    public function moveToBegin(): field
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveToBegin($this->getName());
        return $this;
    }

    public function moveToEnd(): field
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->moveToEnd($this->getName());
        return $this;
    }

    public function detach(): field
    {
        if(is_null($this->fieldList))
        {
            trigger_error('[ZIN] The field named ' . $this->getName() . ' has no fieldList, maybe you should add self to a fieldList firstly.', E_USER_ERROR);
        }
        $this->fieldList->remove($this->getName());
        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        if(isset($array['items']))
        {
            $items = array();
            foreach($array['items'] as $key => $item)
            {
                if($item instanceof \zin\utils\dataset) $item = $item->toArray();
                elseif($item instanceof \stdClass)      $item = get_object_vars($item);
                $items[$key] = $item;
            }
            $array['items'] = $items;
        }
        if(isset($array['control']) && is_array($array['control']) && isset($array['control']['control']) && count($array['control']) == 1)
        {
            $array['control'] = $array['control']['control'];
        }
        return $array;
    }
}
