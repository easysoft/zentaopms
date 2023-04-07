<?php
namespace zin;

class searchForm extends wg
{
    protected function build()
    {
        return zui::searchForm(inherit($this));
    }
}
