<?php
declare(strict_types=1);

namespace zin;

class priPicker extends wg
{
    protected function build()
    {
        return zui::priPicker(inherit($this));
    }
}
