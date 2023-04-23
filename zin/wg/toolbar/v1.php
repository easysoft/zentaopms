<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class toolbar extends wg
{
    protected static $defineProps = 'items?:array, btnClass?:string, btnProps?: array';

    public function onBuildItem($item)
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        $type = $item->prop('type');
        if($type === 'divider') return div(setClass('toolbar-divider'));
        if($type === 'btnGroup') return new btnGroup(inherit($item));

        list($btnClass, $btnProps) = $this->prop(array('btnClass', 'btnProps'));
        return new btn
        (
            setClass('toolbar-item', $btnClass),
            is_array($btnProps) ? set($btnProps) : NULL,
            inherit($item)
        );
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div
        (
            setClass('toolbar'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
