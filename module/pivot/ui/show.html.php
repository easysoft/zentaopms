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
jsVar('drillModalTitle', $this->lang->pivot->stepDrill->drillView);

$fnGenerateFilters = function() use($pivot, $showOrigin, $lang)
{
    $hasFilter = !empty($pivot->filters);
    if($showOrigin || !$hasFilter) return div(setID('conditions'), setClass('mb-4'));

    $filters = array();
    $options = array();
    foreach($pivot->filters as $filter)
    {
        $type   = $filter['type'];
        $name   = $filter['name'];
        $field  = $filter['field'];
        $value  = zget($filter, 'default', '');
        $from   = zget($filter, 'from');
        $values = is_array($value) ? implode(',', $value) : $value;

        $url  = createLink('pivot', 'ajaxGetSysOptions', "search={search}");
        $data = array();
        $data['values'] = $values;
        if($from == 'query')
        {
            $data['type']   = $filter['typeOption'];
            $items = (object)array('url' => $url, 'method' => 'post', 'data' => $data);
            $filters[]  = filter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => $items, 'multiple' => $type == 'multipleselect' ? true : false)));
        }
        else
        {
            $fieldSetting   = $pivot->fieldSettings->$field;
            $data['type']   = $fieldSetting->type;
            $data['object'] = $fieldSetting->object;
            $data['field']  = $fieldSetting->field;
            $data['saveAs'] = zget($filter, 'saveAs', $field);
            $data['sql']    = $pivot->sql;

            $items = (object)array('url' => $url, 'method' => 'post', 'data' => $data);
            $filters[] = resultFilter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => $items)));
        }
    }

    $isSingleFilter = count($filters) == 1;

    return div
    (
        setID('conditions'),
        setClass('flex justify-start bg-canvas mt-4 mb-2 w-full', array('flex-wrap' => $isSingleFilter, 'items-center' => !$isSingleFilter)),
        $isSingleFilter ? $filters : div
        (
            setClass('flex flex-wrap w-full'),
            $filters
        ),
        button(setClass('btn primary mb-2'), on::click('loadCustomPivot'), $lang->pivot->query)
    );
};

$generateData = function() use ($lang, $pivotName, $pivot, $data, $configs, $showOrigin, $fnGenerateFilters)
{
    $clickable = !$pivot->builtin;
    $emptyTip  = $this->pivot->isFiltersAllEmpty($pivot->filters) ? $lang->pivot->filterEmptyVal : $lang->error->noData;
    list($cols, $rows, $cellSpan) = $this->loadModel('bi')->convertDataForDtable($data, $configs);

    return array
    (
        panel
        (
            setID('pivotPanel'),
            set::title($pivotName),
            set::shadow(false),
            set::headingClass('h-12'),
            set::bodyClass('pt-0'),
            to::titleSuffix
            (
                $pivot->desc ? icon
                (
                    setClass('cursor-pointer'),
                    setData(array('toggle' => 'tooltip', 'title' => $pivot->desc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                ) : null,
                span
                (
                    set::style(array('font-weight' => 'normal')),
                    $lang->pivot->tipNewVersion . $lang->comma,
                    h::a
                    (
                        $lang->pivot->checkNewVersion,
                        set('data-toggle', 'modal'),
                        set::url($this->createLink('pivot', 'versions', "pivotID={$pivot->id}"))
                    )
                )
            ),
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
                $this->config->edition != 'open' && $clickable ? array(
                hasPriv('pivot', 'design') ? item(set(array
                (
                    'text'  => $lang->pivot->designAB,
                    'icon'  => 'design',
                    'class' => 'ghost',
                    'url'   => inlink('design', "id=$pivot->id"),
                    'data-confirm' => $this->pivot->checkIFChartInUse($pivot->id, 'pivot') ? array('message' => $lang->pivot->confirm->design, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x') : null
                ))) : null,
                hasPriv('pivot', 'edit') ? item(set(array
                (
                    'text'  => $lang->edit,
                    'icon'  => 'edit',
                    'class' => 'ghost',
                    'url'   => inlink('edit', "id=$pivot->id", '', true),
                    'data-toggle' => 'modal',
                    'data-size'  => 'sm'
                ))) : null,
                hasPriv('pivot', 'delete') ? item(set(array
                (
                    'text'  => $lang->delete,
                    'icon'  => 'trash',
                    'class' => 'ghost ajax-submit',
                    'url'   => inlink('delete', "id=$pivot->id"),
                    'data-confirm' => array('message' => $lang->pivot->deleteTip, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x')
                ))) : null) : null
            ),
            div(setClass('divider')),
            $fnGenerateFilters(),
            dtable
            (
                set::bordered(true),
                set::height(jsRaw('window.getHeight')),
                set::cols($cols),
                set::data($rows),
                set::emptyTip($emptyTip),
                set::rowHover(false),
                set::colHover(false),
                set::onRenderCell(jsRaw('renderCell')),
                set::onCellClick(jsRaw('clickCell')),
                set::rowKey('ROW_ID'),
                set::plugins(array('header-group', 'cellspan')),
                set::getCellSpan(jsRaw('getCellSpan')),
                set::cellSpanOptions($cellSpan)
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
