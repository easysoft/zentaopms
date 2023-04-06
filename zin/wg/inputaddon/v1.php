<?php
namespace zin;

class inputAddon extends wg
{
    protected static $defineProps = 'text?:stirng,wg?:array';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function buildWg($props)
    {
        $wgName = '\\zin\\' . $props['type'];
        unset($props['type']);
        return $wgName(inherit(item(set($props))));
    }

    protected function build()
    {
        $wg = $this->prop('wg');
        if(is_array($wg)) $wg = $this->buildWg($wg);

        return div(
            setClass('input-group-addon'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $wg,
            $this->prop('text'),
            $this->children(),
        );
    }
}
