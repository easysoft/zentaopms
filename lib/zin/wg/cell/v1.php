<?php
declare(strict_types=1);
namespace zin;

class cell extends wg
{
    protected static array $defineProps = array(
        'order?: int',
        'grow?: int',
        'shrink?: int',
        'flex?: string="auto"',  // 'auto'|'none'|string
        'width?: string|int',
        'align?: string'         // 'auto'|'flex-start'|'flex-end'|'center'|'baseline'|'stretch'
    );

    protected function build(): wg
    {
        $basis = null;
        $class = array('cell');
        $width = $this->prop('width');
        $flex  = $this->prop('flex');
        if(!empty($width))
        {
            $basis = $width;
            if(is_numeric($width)) $basis = $width . 'px';
            elseif(preg_match('/^(\d+)\/(\d+)$/', $width, $matches) !== 0) $basis = ((int)$matches[1] / (int)$matches[2] * 100) . '%';
        }
        if(!empty($flex))
        {
            if(strpos($flex, ' ') !== false) $style['flex'] = $flex;
            else                             $class[] = "flex-$flex";
        }

        $style = array();
        $style['order']       = $this->prop('order');
        $style['flex-grow']   = $this->prop('grow');
        $style['flex-shrink'] = $this->prop('shrink');
        $style['flex-basis']  = $basis;
        $style['align-self']  = $this->prop('align');

        return div
        (
            setClass($class),
            setStyle($style),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
