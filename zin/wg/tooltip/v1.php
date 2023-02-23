<?php
namespace zin;

class tooltip extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        $tooltip = zui::tooltip(inherit($this));

        return $tooltip;
    }
}
