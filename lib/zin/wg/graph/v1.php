<?php
declare(strict_types=1);
namespace zin;

class graph extends wg
{
    protected static array $defineProps = array(
        'type?: string', // 'TreeGraph' or 'Graph', default is 'TreeGraph'
        'responsive?: bool=false',
        'width?: number|string="100%"',
        'height?: number|string="500"',
        'graphID?: number=0',
    );

    protected function build(): zui
    {
        list($type, $width, $height, $responsive, $graphID) = $this->prop(array('type', 'width', 'height', 'responsive', 'graphID'));
        if(empty($graphID)) $graphID = uniqid();


        return zui::graph(
            set::_id('zin_graph_' . $graphID),
            set::type($type),
            set::responsive($responsive),
            set::_style(array('width' => is_int($width) ? "{$width}px" : $width, 'height' => is_int($height) ? "{$height}px" : $height)),
            set($this->getRestProps()),
        );
    }
}
