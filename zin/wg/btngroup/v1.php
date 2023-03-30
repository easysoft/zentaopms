<?php
namespace zin;

class btngroup extends wg
{
    static $defineProps = [
        'items?:array',
        'disabled?:bool',
        'size?:string',
    ];

    protected function build()
    {
        $items    = $this->prop('items');
        $disabled = $this->prop('disabled');
        $size     = $this->prop('size');

        $classList = 'btn-grouop';
        if(!empty($disabled)) $classList .= ' disabled';
        if(!empty($size))     $classList .= " size-$size";

        if(empty($items))
        {
            return div
            (
                setClass($classList),
                $this->children(),
            );
        }

        return zui::btngroup(inherit($this));
    }
}
