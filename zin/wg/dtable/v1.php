<?php
namespace zin;

class dtable extends wg
{
    static $defineProps = 'className?:string="shadow rounded"';

    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
