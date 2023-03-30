<?php

namespace zin;

class cell extends wg
{
    static $defineProps = 'order,grow,shrink,width,align,flex';

    protected function build()
    {
        $basis = empty($this->prop('width')) ? 'auto' : $this->prop('width');
        if(is_numeric($basis)) $basis .= 'px';
        elseif(preg_match('/^(\d+)\/(\d+)$/', $basis, $matches) !== 0) $basis = ((int)$matches[1] / (int)$matches[2] * 100) . '%';

        $style = array();
        $style['order']       = $this->prop('order');
        $style['flex-grow']   = $this->prop('grow');
        $style['flex-shrink'] = $this->prop('shrink');
        $style['flex-basis']  = $basis;
        $style['align-self']  = $this->prop('align');
        $style['flex']        = $this->prop('flex');

        return div
        (
            setStyle($style),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
