<?php

namespace zin;

use stdClass;

class cell extends wg
{
    static $defineProps = 'order,grow,shrink,width,align,flex';

    public function onAddChild($child)
    {
        if($child instanceof wg) {
            $this->addToBlock('inner', $child);
            return false;
        }
        return null;
    }

    protected function build()
    {
        $basis  = empty($this->prop('width')) ? 'auto' : $this->prop('width');
        if(is_numeric($basis)) $basis .= 'px';
        elseif(preg_match('/^(\d+)\/(\d+)$/', $basis, $matches) !== 0) {
            $basis = ((int)$matches[1] / (int)$matches[2] * 100) . '%';
        }

        $style = new stdClass();
        if(!empty($this->prop('order')))  $style['order']       = $this->prop('order');
        if(!empty($this->prop('grow')))   $style['flex-grow']   = $this->prop('grow');
        if(!empty($this->prop('shrink'))) $style['flex-shrink'] = $this->prop('shrink');
        if(!empty($this->prop('width')))  $style['flex-basis']  = $this->prop('width');
        if(!empty($this->prop('align')))  $style['align-self']  = $this->prop('align');
        if(!empty($this->prop('flex')))   $style['flex']        = $this->prop('flex');
        return div(
            setStyle($style),
            $this->block('inner'),
        );
    }
}
