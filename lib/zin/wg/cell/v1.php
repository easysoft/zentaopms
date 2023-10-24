<?php
declare(strict_types=1);
namespace zin;

class cell extends wg
{
    protected static array $defineProps = array(
        'flex?: string',        // flex 类型或具体的值，例如：'auto'、'none'、'1'、'auto 1 1'。
        'order?: int',          // flex-order 属性。
        'grow?: int',           // flex-grow 属性。
        'shrink?: int',         // flex-shrink 属性。
        'width?: string|int',   // flex-basis 属性，支持数值或百分比，例如 128px、1/3、30%、128px。
        'align?: string'        // align-self 属性，例如 'auto'、'flex-start'、'flex-end'、'center'、'baseline'、'stretch'。
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
