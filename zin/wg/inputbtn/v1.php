<?php
namespace zin;

class inputBtn extends wg
{
    protected static $defineProps = 'wg?:array';

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
            setClass('input-group-btn'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $wg,
            $this->children()
        );
    }
}
