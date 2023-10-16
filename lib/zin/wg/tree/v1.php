<?php
declare(strict_types=1);
namespace zin;

class tree extends wg
{
    protected function build(): zui
    {
        return zui::tree(set::_tag('ul'), inherit($this));
    }
}
