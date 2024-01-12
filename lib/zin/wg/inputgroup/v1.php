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
        if(is_string($item)) $item = new item(set(array('type' => 'addon', 'text' => $item)));
        elseif(is_array($item)) $item = new item(set($item));
        elseif($item instanceof wg || is_null($item)) return $item;

        $type = $item->prop('type');

        if($type === 'addon')      return h::span(setClass('input-group-addon'), set($item->props->skip('type,text')), $item->prop('text'));
        if($type === 'span')       return h::span(setClass('px-2 h-8 flex items-center'), set($item->props->skip('type,text')), $item->prop('text'));
        if($type === 'btn')        return new btn(set($item->props->skip('type')));
        if($type === 'picker')     return new picker(set($item->props->skip('type')));
        if($type === 'datePicker') return new datePicker(set($item->props->skip('type')));

        if($type)
        {
            $propNames = array_keys(inputControl::definedPropsList());
            return new inputControl
            (
                set($item->props->pick($propNames)),
                new input(set($item->props->skip(array_merge($propNames, array('type')))))
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
