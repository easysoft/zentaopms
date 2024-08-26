<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';

class sqlBuilderGroupBy extends wg
{
    protected static array $defineProps = array(
        'groups?: array',
        'aggs?: array'
    );

    protected function buildHelpIcon(string $text): node
    {
        if(empty($text)) return span
        (
            setClass('text-warning'),
            icon('help'),
        );

        return btn
        (
            setClass('inline ghost'),
            set::size('sm'),
            setData(array('title' => $text, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white', 'hideOthers' => true)),
            set('data-toggle', 'tooltip'),
            icon('help')
        );
    }

    protected function build()
    {
        global $lang;
        return div
        (
            setClass('flex row w-full gap-x-4'),
            panel
            (
                setClass('w-80 h-78'),
                set::headingClass('bg-gray-100'),
                set::title($lang->bi->allFields),
                to::heading($this->buildHelpIcon($lang->bi->allFieldsTip))
            ),
            panel
            (
                setClass('w-60 h-78'),
                set::headingClass('bg-gray-100'),
                set::title($lang->bi->groupField),
                to::heading($this->buildHelpIcon($lang->bi->groupFieldTip))
            ),
            panel
            (
                setClass('flex-auto h-78'),
                set::headingClass('bg-gray-100'),
                set::title($lang->bi->aggField),
                to::heading($this->buildHelpIcon($lang->bi->aggFieldTip))
            )
        );
    }
}
