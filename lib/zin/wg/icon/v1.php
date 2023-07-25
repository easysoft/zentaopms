<?php
declare(strict_types=1);
namespace zin;

class icon extends wg
{
    protected static array $defineProps = array(
        'name: string',
        'size?: string|int'
    );

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('name'))
        {
            $this->props->set('name', $child);
            return false;
        }
    }

    protected function build(): wg
    {
        list($name, $size) = $this->prop(array('name', 'size'));
        return h::i
        (
            setClass('icon', empty($name) ? null : "icon-$name"),
            is_numeric($size)
                ? setStyle('font-size', "{$size}px")
                : (is_string($size)
                    ? setClass("icon-$size")
                    : null),
            set($this->props->skip(array_keys(icon::definedPropsList()))),
            $this->children()
        );
    }
}
