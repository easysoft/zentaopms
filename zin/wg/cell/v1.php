<?php

namespace zin;

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
        $order  = empty($this->prop('order')) ? '0' : $this->prop('order');
        $grow   = empty($this->prop('grow')) ? '0' : $this->prop('grow');
        $shrink = empty($this->prop('shrink')) ? '1' : $this->prop('shrink');
        $basis  = empty($this->prop('width')) ? 'auto' : $this->prop('width');
        $align  = empty($this->prop('align')) ? 'auto' : $this->prop('align');
        $flex   = empty($this->prop('flex')) ? '0 1 auto' : $this->prop('flex');

        if(is_numeric($basis)) $basis .= 'px';
        elseif(preg_match('/^(\d+)\/(\d+)$/', $basis, $matches) !== 0) {
            $basis = ((int)$matches[1] / (int)$matches[2] * 100) . '%';
        }
        return div(
            setStyle(array(
                'order'       => $order,
                'flex-grow'   => $grow,
                'flex-shrink' => $shrink,
                'basis'       => $basis,
                'align-self'  => $align,
                'flex'        => $flex
            )),
            $this->block('inner'),
        );
    }
}
