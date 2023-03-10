<?php
namespace zin;

class sidemenu extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        return zui::sidemenu(inherit($this));
    }
}
