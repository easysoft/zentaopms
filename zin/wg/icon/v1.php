<?php
namespace zin;

class icon extends wg
{
    protected static $defineProps = 'name:string';

    protected function build()
    {
        $iconName = $this->props->get('name', '');
        return h::i
        (
            setClass("icon icon-$iconName"),
            set($this->props->skip('name')),
            $this->children()
        );
    }

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('name'))
        {
            $this->props->set('name', $child);
            return false;
        }
    }
}
