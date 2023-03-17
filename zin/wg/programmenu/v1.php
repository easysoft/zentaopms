<?php
namespace zin;

class programmenu extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        return zui::programmenu(inherit($this));
    }
}
