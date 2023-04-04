<?php
namespace zin;

class inputGroup extends wg
{
    protected static $defineProps = 'items?:array';

    public function onBuildItem($item)
    {
        $type = $item['type'];
        unset($item['type']);
        $item = inherit(item(set($item)));

        if($type === 'addon')
        {
            return inputAddon($item);
        }

        if($type === 'btn')
        {
            return inputBtn($item);
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
