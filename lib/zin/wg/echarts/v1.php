<?php
declare(strict_types=1);
namespace zin;

class echarts extends wg
{
    public function size(string|int $width, string|int $height)
    {
        if(is_numeric($width))  $width  = "{$width}px";
        if(is_numeric($height)) $height = "{$height}px";
        $this->setProp('_size', array($width, $height));
    }

    protected function build()
    {
        return zui::echarts(inherit($this));
    }
}
