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

        $items = $this->getFilterOptionUrl($filter, $pivot->sql, (array)$pivot->fieldSettings);
        if($from == 'query')
        {
            $filters[]  = filter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => $items, 'multiple' => $type == 'multipleselect' ? true : false)));
        }
        else
        {
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

$generateData = function() use ($lang, $groupID, $pivotName, $pivot, $data, $configs, $showOrigin, $fnGenerateFilters, $hasVersionMark)
{
    $clickable = $this->config->edition != 'open';
    $emptyTip  = $this->pivot->isFiltersAllEmpty($pivot->filters) ? $lang->pivot->filterEmptyVal : $lang->error->noData;
    list($cols, $rows, $cellSpan) = $this->loadModel('bi')->convertDataForDtable($data, $configs, $pivot->version, 'published');

    return array
    (
        panel
        (
            setID('pivotPanel'),
            $this->app->rawMethod != 'versions' ? set::title($pivotName) : null,
            set::shadow(false),
            set::headingClass('h-12'),
            set::bodyClass('pt-0'),
            to::titleSuffix
            (
                setClass(array('hidden' => $this->app->rawMethod == 'versions')),
                icon
                (
                    setClass('cursor-pointer', array('hidden' => !$pivot->desc)),
                    setData(array('toggle' => 'tooltip', 'title' => $pivot->desc, 'placement' => 'right', 'className' => 'text-gray border border-light', 'type' => 'white')),
                    'help'
                )
                /**
                span
                (
                    set::style(array('font-weight' => 'normal')),
                    setClass(array('hidden' => !hasPriv('pivot', 'design') || !$pivot->versionChange || $hasVersionMark)),
                    $lang->pivot->tipNewVersion . $lang->comma,
                    h::a
                    (
                        $lang->pivot->checkNewVersion,
                        set('data-toggle', 'modal'),
                        set('data-size', 'lg'),
                        set::href($this->createLink('pivot', 'versions', "groupID={$groupID}&pivotID={$pivot->id}"))
                    )
                )
                */
            ),
            toolbar
            (
                setClass(array('hidden' => $this->app->rawMethod == 'versions')),
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
                item(set(array
                (
                    'text'  => $lang->pivot->designAB,
                    'icon'  => 'design',
                    'class' => array('ghost', 'hidden' => !hasPriv('pivot', 'design') || !$clickable),
                    'url'   => inlink('design', "id=$pivot->id"),
                    'data-confirm' => $this->pivot->checkIFChartInUse($pivot->id, 'pivot') ? array('message' => $lang->pivot->confirm->design, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x') : null
                ))),
                item(set(array
                (
                    'text'  => $lang->edit,
                    'icon'  => 'edit',
                    'class' => array('ghost', 'hidden' => !hasPriv('pivot', 'edit') || !$clickable),
                    'url'   => inlink('edit', "id=$pivot->id", '', true),
                    'data-toggle' => 'modal',
                    'data-size'  => 'sm'
                ))),
                item(set(array
                (
                    'text'  => $lang->delete,
                    'icon'  => 'trash',
                    'class' => array('ghost ajax-submit', 'hidden' => !hasPriv('pivot', 'delete') || !$clickable),
                    'url'   => inlink('delete', "id=$pivot->id"),
                    'data-confirm' => array('message' => $lang->pivot->deleteTip, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x')
                )))
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
                set::onCellClick(jsRaw('clickCell')),
                set::rowKey('ROW_ID'),
                set::plugins(array('header-group', 'cellspan')),
                set::cellSpanOptions($cellSpan),
                set::getCellSpan(jsRaw(<<<JS
                function(cell)
                {
                    const options = this.options.cellSpanOptions[cell.col.name];
                    if(options)
                    {
                        const rowSpan = cell.row.data[options.rowspan ?? 'rowspan'] ?? 1;
                        const colSpan = cell.row.data[options.colspan ?? 'colspan'] ?? 1;
                        return {rowSpan, colSpan};
                    }
                }
                JS)),
                set::onRenderCell(jsRaw(<<<JS
                function(result, {row, col})
                {
                    if(result)
                    {
                        let values  = result.shift();
                        let isDrill = row.data.isDrill[col.name];
                        let isTotal = row.data.isTotal;
                        if(col.setting.colspan && typeof(values.type) != 'undefined' && values.type == 'a')
                        {
                            values = values.props['children'];
                            result.push({className: 'gap-0 p-0.5'});
                            values.forEach((value, index) =>
                              result.push({
                                html: value + '' || !Number.isNaN(value) ? (isDrill && index == 0 ? "<a href='#'>" + `\${value}` + '</a>' : `\${value}`) : '&nbsp;',
                                className: 'flex justify-center items-center h-full w-1/2' + (index == 0 ? ' border-r': ''),
                                style: 'border-color: var(--dtable-border-color)' + (isTotal ? '; background-color: var(--color-surface-light);' : '')
                              })
                            );
                        }
                        else
                        {
                            if(!isDrill && values?.type == 'a') values = values.props.children;
                            if(isTotal)
                            {
                                result.push({className: 'gap-0 p-0.5'});
                                values = {
                                    html: values + '',
                                    className: 'flex justify-center items-center h-full w-full',
                                    style: 'border-color: var(--dtable-border-color)' + (isTotal ? '; background-color: var(--color-surface-light);' : '')
                                };
                            }
                            result.push(values);
                        }
                    }

                    return result;
                }
                JS))
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
