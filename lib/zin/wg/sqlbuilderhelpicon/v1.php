<?php
declare(strict_types=1);
namespace zin;

class sqlBuilderHelpIcon extends wg
{
    protected static array $defineProps = array(
        'text?: string',
        'placement?: string="right"',
        'toggle?: bool=true'
    );

    protected function build()
    {
        list($text, $placement, $toggle) = $this->prop(array('text', 'placement', 'toggle'));

        if(empty($text)) return span
        (
            setClass('text-warning'),
            icon('help'),
        );

        return btn
        (
            setClass('inline ghost'),
            set::size('sm'),
            setData(array('title' => $text, 'placement' => $placement, 'className' => 'text-gray border border-light', 'type' => 'white', 'hideOthers' => true)),
            $toggle ? set('data-toggle', 'tooltip') : null,
            icon('help')
        );
    }
}
