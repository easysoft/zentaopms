<?php
namespace zin;

class colorPicker extends wg
{
    protected function build()
    {
        return zui::colorPicker(inherit($this));
    }
}
