<?php
declare(strict_types=1);
namespace zin;

class searchForm extends wg
{
    protected function build()
    {
        return zui::searchForm(inherit($this));
    }
}
