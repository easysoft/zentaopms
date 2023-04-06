<?php
namespace zin;

class dtable extends wg
{
    protected function build()
    {
        return zui::dtable(inherit($this));
    }
}
