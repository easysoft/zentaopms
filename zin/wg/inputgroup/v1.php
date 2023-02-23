<?php
namespace zin;

class inputgroup extends wg
{
    protected static $defineProps = 'items?:array';

    public function onBuildItem($item)
    {
        $type = $item['type'];
        unset($item['type']);
        $item = inherit(item(set($item)));

        if($type === 'addon')
        {
            return inputaddon($item);
        }

        if($type === 'btn')
        {
            return inputbtn($item);
        }

        if($type === 'input')
        {
            return input(
                $item,
                setClass('form-control'),
            );
        }
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div(
            setClass('input-group'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            $this->children()
        );
    }
}
