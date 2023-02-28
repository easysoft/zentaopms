<?php
namespace zin;

class dtable extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
