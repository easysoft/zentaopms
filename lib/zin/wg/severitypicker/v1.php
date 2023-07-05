<?php
declare(strict_types=1);

namespace zin;

class severityPicker extends wg
{
    protected function build()
    {
        return zui::severityPicker(inherit($this));
    }
}
