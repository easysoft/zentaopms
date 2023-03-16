<?php
namespace zin;

class searchToggle extends wg
{
    protected function build()
    {
        global $lang;
        return btn
        (
            set::class('ghost'),
            set::icon('search'),
            set::text($lang->searchAB)
        );
    }
}
