<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'picker' . DS . 'v1.php';

class inputGroup extends wg
{
    protected static array $defineProps = array(
        'items?:array',
        'seg?:bool'
    );

    public function onBuildItem($item): ?wg
    {
        if(is_string($item)) $item = new item(set(array('control' => 'addon', 'text' => $item)));
        elseif(is_array($item)) $item = new item(set($item));
        elseif($item instanceof wg || is_null($item)) return $item;

        list($control, $type) = $item->prop(array('control', 'type'));
        if(is_null($control) && !is_null($type))
        {
            $control = $type;
            $type    = null;
            $item->setProp('control', $control);
            $item->setProp('type', null);
        }
        if(is_array($control))
        {
            $control = $control['control'];
        }

        if($control === 'addon')      return h::span(setClass('input-group-addon'), set($item->props->skip('control,text')), $item->prop('text'));
        if($control === 'span')       return h::span(setClass('px-2 h-8 flex items-center'), set($item->props->skip('control,text')), $item->prop('text'));
        if($control === 'btn')        return new btn(set($item->props->skip('control')));
        if($control === 'picker')     return new picker(set($item->props->skip('control')));
        if($control === 'datePicker') return new datePicker(set($item->props->skip('control')));

        if($control)
        {
            $propNames = array_keys(inputControl::definedPropsList());
            return new inputControl
            (
                set($item->props->pick($propNames)),
                new input(set($item->props->skip(array_merge($propNames, array('control')))))
            );
        }

        return new input(inherit($item));
    }

    protected function build(): wg
    {
        list($items, $seg) = $this->prop(['items', 'seg']);
        $children = $this->children();

        return div
        (
            setClass('input-group', $seg ? 'input-group-segment' : null),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            is_array($children) ? array_map(array($this, 'onBuildItem'), $children) : null
        );
    }
}
