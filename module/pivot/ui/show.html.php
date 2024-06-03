<?php
declare(strict_types = 1);
/**
 * The show view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('currentGroup', $currentGroup);
jsVar('pivotID', $pivot->id);

$filters = array();
$options = array();
foreach($pivot->filters as $filter)
{
    $type  = $filter['type'];
    $name  = $filter['name'];
    $field = $filter['field'];
    $value = zget($filter, 'default', '');
    $from  = zget($filter, 'from');
    if($from == 'query')
    {
        $typeOption = $filter['typeOption'];
        if($type == 'select' && !isset($options[$typeOption])) $options[$typeOption] = $this->pivot->getSysOptions($typeOption);

        $filters[] = filter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => zget($options, $typeOption, array()))));
    }
    else
    {
        if($type == 'select' && !isset($options[$field]))
        {
            $fieldSetting = $pivot->fieldSettings->$field;
            $options[$field] = $this->pivot->getSysOptions($fieldSetting->type, $fieldSetting->object, $fieldSetting->field, $pivot->sql, zget($filter, 'saveAs', ''));
        }

        $filters[] = resultFilter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => zget($options, $field, array()))));
    }
}

$generateData = function() use ($lang, $pivotName, $pivot, $filters, $data, $configs)
{
    $clickable = !$pivot->builtin;
    list($cols, $rows, $cellSpan) = $this->convertDataForDtable($data, $configs);

    return array
    (
        panel
        (
            setID('pivotPanel'),
            set::title($pivotName),
            set::shadow(false),
            set::headingClass('h-12'),
            set::bodyClass('pt-0'),
            $pivot->desc ? to::titleSuffix(
                icon
                (
                    setClass('cursor-pointer'),
                    setData(array('toggle' => 'tooltip', 'title' => $pivot->desc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                )
            ) : null,
            toolbar
            (
                item
                (
                    setID('origin-query'),
                    setClass('ghost'),
                    set::icon('exchange'),
                    set::text($lang->pivot->showOrigin),
                    on::click("toggleShowMode('origin')"),
                ),
                item
                (
                    setID('pivot-query'),
                    setClass('ghost hidden'),
                    set::icon('exchange'),
                    set::text($lang->pivot->showPivot),
                    on::click("toggleShowMode('group')"),
                ),
                $this->config->edition != 'open' ? array(
                (hasPriv('pivot', 'design') && $clickable) ? item(set(array
                (
                    'text'  => $lang->pivot->designAB,
                    'icon'  => 'design',
                    'class' => 'ghost',
                    'url'   => inlink('design', "id=$pivot->id"),
                ))) : null,
                (hasPriv('pivot', 'edit') && $clickable) ? item(set(array
                (
                    'text'  => $lang->edit,
                    'icon'  => 'edit',
                    'class' => 'ghost',
                    'url'   => inlink('edit', "id=$pivot->id", '', true),
                    'data-toggle' => 'modal',
                    'data-size'  => 'sm'
                ))) : null,
                (hasPriv('pivot', 'delete') and $clickable) ? item(set(array
                (
                    'text'  => $lang->delete,
                    'icon'  => 'trash',
                    'class' => 'ghost ajax-submit',
                    'url'   => inlink('delete', "id=$pivot->id&confirm=yes&isOld=no"),
                    'data-confirm' => $lang->pivot->deleteTip,
                ))) : null) : null
            ),
            div(setClass('divider')),
            $filters ? div
            (
                setID('conditions'),
                setClass('flex justify-between bg-canvas mt-4 mb-2'),
                div
                (
                    setClass('flex flex-wrap w-full'),
                    $filters
                ),
                button(setClass('btn primary'), on::click('loadCustomPivot'), $lang->pivot->query)
            ) : div(setClass('mb-4')),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::height(jsRaw('window.getHeight')),
                set::cols($cols),
                set::data($rows),
                set::emptyTip($lang->error->noData),
                set::onRenderCell(jsRaw('renderCell')),
                set::plugins(array('header-group', $cellSpan ? 'cellspan' : null)),
                $cellSpan ? set::getCellSpan(jsRaw('getCellSpan')) : null,
                $cellSpan ? set::cellSpanOptions($cellSpan) : null
            ),
            div
            (
                setID('exportData'),
                setClass('hidden'),
                rawContent(),
                $this->pivot->buildPivotTable($data, $configs),
            )
        )
    );
};
