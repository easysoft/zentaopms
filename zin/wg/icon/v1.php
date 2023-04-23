<?php
namespace zin;

class icon extends wg
{
    protected static $defineProps = 'name:string, size?:string|number';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('name'))
        {
            $this->props->set('name', $child);
            return false;
        }
    }

    protected function build()
    {
        list($name, $size) = $this->prop(array('name', 'size'));
        return h::i
        (
            setClass('icon', empty($name) ? NULL : "icon-$name"),
            is_numeric($size)
                ? setStyle('font-size', "{$size}px")
                : (is_string($size)
                    ? setClass("icon-$size")
                    : NULL),
            set($this->props->skip(array_keys(icon::getDefinedProps()))),
            $this->children()
        );
    }
}
