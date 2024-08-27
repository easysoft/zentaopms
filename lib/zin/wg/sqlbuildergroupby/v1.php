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
        list($table, $field, $function, $alias, $name) = array($agg['table'], $agg['field'], $agg['function'], $agg['alias'], $agg['name']);
        return formRow
        (
            sqlBuilderPicker
            (
                set::name("{$table}_{$field}"),
                set::label(sprintf($lang->bi->aggTipA, $name)),
                set::labelWidth('176px'),
                set::labelAlign('left'),
                set::width('64'),
                set::required(true),
                set::items($lang->bi->aggList),
                set::placeholder($lang->bi->selectFuncTip),
                set::value($function)
            ),
            sqlBuilderControl
            (
                set::type(''),
                set::label(sprintf($lang->bi->aggTipB, $alias)),
                set::labelWidth('320px'),
                set::width('96'),
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

    protected function buildGroupField()
    {
        list($groups) = $this->prop(array('groups'));
        $items = array();
        foreach($groups as $index => $group) if($group['type'] == 'group') $items[] = array('text' => $group['name'], 'data-index' => $index);

        return zui::SortableList
        (
            setClass('group-by-sort'),
            set::items($items),
            set::itemProps(array('icon' => 'move muted'))
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
                setClass('basis-72 h-78'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->allFields),
                to::heading($this->buildHelpIcon($lang->bi->allFieldsTip)),
                $this->buildAllField()
            ),
            panel
            (
                setClass('basis-36 h-78'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->groupField),
                to::heading($this->buildHelpIcon($lang->bi->groupFieldTip)),
                $this->buildGroupField()
            ),
            panel
            (
                setClass('flex-1 h-78'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->aggField),
                to::heading($this->buildHelpIcon($lang->bi->aggFieldTip)),
                $this->buildAggField()
            )
        );
    }
}
