<?php
namespace zin;

class searchForm extends wg
{
    protected static $defineProps = array
    (
        'js-render?:bool=true'
    );

    protected function build()
    {
        return zui::searchForm(inherit($this));
    }
}
