<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';

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

    protected function buildFieldItem(int $index, array $group): node
    {
        global $lang;
        list($name, $type) = array($group['name'], $group['type']);

        return div
        (
            setClass('flex row gap-x-4 justify-between'),
            span($name),
            btnGroup
            (
                btn
                (
                    setClass('switch-group-by'),
                    set::size('sm'),
                    set::type($type == 'group' ? 'secondary' : 'default'),
                    set('data-index', $index),
                    set('data-type', 'group'),
                    $lang->bi->groupField
                ),
                btn
                (
                    setClass('switch-group-by'),
                    set::size('sm'),
                    set::type($type == 'agg' ? 'secondary' : 'default'),
                    set('data-index', $index),
                    set('data-type', 'agg'),
                    $lang->bi->aggField
                )
            )
        );
    }

    protected function buildAllField()
    {
        list($groups) = $this->prop(array('groups'));
        $items = array();
        foreach($groups as $index => $group) $items[] = $this->buildFieldItem($index, $group);

        return $items;
    }

    protected function buildAggFieldRow($agg)
    {
        global $lang;
        list($table, $field, $function, $alias) = array($agg['table'], $agg['field'], $agg['function'], $agg['alias']);
        return formRow
        (
            sqlBuilderPicker
            (
                set::name("agg{$table}{$field}"),
                set::label(sprintf($lang->bi->aggTipA, $table, $field)),
                set::labelWidth('256px'),
                set::labelAlign('left'),
                set::width('96'),
                set::items($lang->bi->aggList),
                set::placeholder($lang->bi->selectFuncTip),
                set::value($function)
            ),
            sqlBuilderControl
            (
                set::type(''),
                set::label(sprintf($lang->bi->aggTipB, $alias)),
                set::labelWidth('256px'),
                set::labelAlign('left')
            )
        );
    }

    protected function buildAggField()
    {
        list($aggs) = $this->prop(array('aggs'));

        $formRows = array();
        foreach($aggs as $agg) $formRows[] = $this->buildAggFieldRow($agg);

        return $formRows;
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
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->allFields),
                to::heading($this->buildHelpIcon($lang->bi->allFieldsTip)),
                $this->buildAllField()
            ),
            panel
            (
                setClass('w-60 h-78'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->groupField),
                to::heading($this->buildHelpIcon($lang->bi->groupFieldTip))
            ),
            panel
            (
                setClass('flex-auto h-78'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->aggField),
                to::heading($this->buildHelpIcon($lang->bi->aggFieldTip)),
                $this->buildAggField()
            )
        );
    }
}
