<?php
declare(strict_types=1);
namespace zin;

class tooltip extends wg
{
    protected function build()
    {
        $tooltip = zui::tooltip(inherit($this));

        return $tooltip;
    }
}
