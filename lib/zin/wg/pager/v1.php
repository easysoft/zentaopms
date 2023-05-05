<?php
namespace zin;

class pager extends wg
{
    protected function build()
    {
        return zui::pager(inherit($this));
    }
}
