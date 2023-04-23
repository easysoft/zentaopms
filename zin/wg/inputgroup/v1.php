<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';

class inputGroup extends wg
{
    protected static $defineProps = 'items?:array, seg?:bool';

    public function onBuildItem($item)
    {
        if(is_string($item)) $item = new item(set(['type' => 'addon', 'text' => $item]));
        elseif(is_array($item)) $item = new item(set($item));
        elseif($item instanceof wg) return $item;

        $type = $item->prop('type');

        if($type === 'addon') return h::span(setClass('input-group-addon'), set($item->props->skip('type,text')), $item->prop('text'));

        if($type === 'btn') return new btn(set($item->props->skip('type')));

        if($type === 'inputControl')
        {
            $propNames = array_keys(inputControl::getDefinedProps());
            return new inputControl
            (
                set($item->props->pick($propNames)),
                new input(set($item->props->skip(array_merge($propNames, ['type']))))
            );
        }

        return new input(inherit($item));
    }

    protected function build()
    {
        list($items, $seg) = $this->prop(['items', 'seg']);
        $children = $this->children();

        return div
        (
            setClass('input-group', $seg ? 'input-group-segment' : NULL),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            is_array($children) ? array_map(array($this, 'onBuildItem'), $children) : NULL,
        );
    }
}
