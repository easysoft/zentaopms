<?php
namespace zin;

class pageToolbar extends wg
{
    private function buildGlobalCreate()
    {
        $props = $this->prop('create');

        if(!isset($props['data-arrow']))   $props['data-arrow']   = true;
        if(!isset($props['data-toggle']))  $props['data-toggle']  = 'dropdown';
        if(!isset($props['data-trigger'])) $props['data-trigger'] = 'hover';
        if(!isset($props['href']))         $props['href']         = '#globalCreateMenu';

        return h::div
        (
            setClass('globalCreate'),
            h::div
            (
                set($props),
                setClass('rounded-sm btn square size-sm secondary'),
                icon('plus')
            )
        );
    }

    private function buildSwitcher()
    {
        $props = $this->prop('switcher');

        if(!isset($props['data-arrow']))   $props['data-arrow']   = true;
        if(!isset($props['data-toggle']))  $props['data-toggle']  = 'dropdown';
        if(!isset($props['data-trigger'])) $props['data-trigger'] = 'hover';
        if(!isset($props['href']))         $props['href']         = '#globalCreateMenu';

        return h::div
        (
            setClass('vision-switcher'),
            h::div
            (
                set($props),
                setClass('switcher-text'),
                $props['text']
            )
        );
    }

    protected function build()
    {
        return h::div
        (
            setId('toolbar'),
            $this->buildGlobalCreate(),
            $this->block('avatar'),
            $this->buildSwitcher()
        );
    }
}
