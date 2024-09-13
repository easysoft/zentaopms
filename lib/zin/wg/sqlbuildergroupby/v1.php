<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sqlbuilderpicker' . DS . 'v1.php';

class sqlBuilderGroupBy extends wg
{
    protected static array $defineProps = array(
        'groups?: array',
        'aggs?: array',
        'onChangeAgg?: function',
        'onChangeType?: function',
        'onSort?: function'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildFieldItem(int $index, array $group): node
    {
        global $lang;
        list($name, $type) = array($group['name'], $group['type']);
        list($onChangeType) = $this->prop(array('onChangeType'));

        return div
        (
            setClass('flex row gap-x-4 justify-between'),
            span
            (
                setClass('ellipsis'),
                set::title($name),
                $name
            ),
            btnGroup
            (
                btn
                (
                    setClass('switch-group-by'),
                    set::size('sm'),
                    set::type($type == 'group' ? 'secondary' : 'default'),
                    set('data-index', $index),
                    set('data-type', 'group'),
                    $lang->bi->groupField,
                    on::click()->do($onChangeType)
                ),
                btn
                (
                    setClass('switch-group-by'),
                    set::size('sm'),
                    set::type($type == 'agg' ? 'secondary' : 'default'),
                    set('data-index', $index),
                    set('data-type', 'agg'),
                    $lang->bi->aggField,
                    on::click()->do($onChangeType)
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
        list($onChangeAgg) = $this->prop(array('onChangeAgg'));
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
                set::value($function),
                set::onChange($onChangeAgg)
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
        list($groups, $onSort) = $this->prop(array('groups', 'onSort'));
        usort($groups, function($a, $b) {return $a['order'] <= $b['order'] ? -1 : 1;});
        $items = array();
        foreach($groups as $index => $group) if($group['type'] == 'group') $items[] = array('text' => $group['name'], 'data-index' => $index);

        return zui::SortableList
        (
            setClass('group-by-sort'),
            set::items($items),
            set::itemProps(array('icon' => 'move muted')),
            set::onSort(jsCallback()->do($onSort))
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
                setID('allFieldPanel'),
                setClass('basis-72 h-72'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->allFields),
                to::heading(sqlBuilderHelpIcon(set::text($lang->bi->allFieldsTip))),
                $this->buildAllField()
            ),
            panel
            (
                setID('groupFieldPanel'),
                setClass('basis-36 h-72'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-70 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->groupField),
                to::heading(sqlBuilderHelpIcon(set::text($lang->bi->groupFieldTip))),
                $this->buildGroupField()
            ),
            panel
            (
                setID('aggFieldPanel'),
                setClass('flex-1 h-72'),
                set::headingClass('bg-gray-100'),
                set::bodyClass('h-64 overflow-y-auto flex col gap-y-2'),
                set::title($lang->bi->aggField),
                to::heading(sqlBuilderHelpIcon(set::text($lang->bi->aggFieldTip))),
                $this->buildAggField()
            )
        );
    }
}
