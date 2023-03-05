<?php
namespace zin;

class searchform extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        return zui::searchform(inherit($this));
    }
}
