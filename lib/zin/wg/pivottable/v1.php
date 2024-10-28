<?php
declare(strict_types=1);
namespace zin;

class pivotTable extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'class?: string',
        'width?: string',
        'cols?: array',
        'data?: array',
        'cellSpan?: array',
        'filters?: array',
        'onRenderCell?: function',
        'onCellClick?: function'
    );

    protected static array $defineBlocks = array(
        'heading'     => array()
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildFilters()
    {
        global $lang;
        list($filters) = $this->prop(array('filters'));

        if(empty($filters)) return div(setID('conditions'), setClass('mb-4'));

        return div
        (
            setID('conditions'),
            setClass('flex justify-start bg-canvas mt-4 mb-2 w-full' . (count($filters) == 1 ? ' flex-wrap' : ' items-center')),
            count($filters) == 1 ? $filters : div
            (
                setClass('flex flex-wrap w-full'),
                $filters
            ),
            button(setClass('btn primary mb-2 load-custom-pivot'), $lang->pivot->query)
        );
    }

    protected function buildDTable()
    {
        global $lang;
        list($cols, $data, $cellSpan, $filters, $onRenderCell, $onCellClick) = $this->prop(array('cols', 'data', 'cellSpan', 'filters', 'onRenderCell', 'onCellClick'));

        $filterCount    = count($filters);
        $filterAllEmpty = $filterCount ? empty(array_filter(array_column($filters, 'default'))) : false;
        $emptyTip       = $filterAllEmpty ? $lang->pivot->filterEmptyVal : $lang->pivot->noPivotTip;
        if(empty($onRenderCell)) $onRenderCell = jsRaw(<<<JS
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
                        html: value || !Number.isNaN(value) ? (isDrill && index == 0 ? "<a href='#'>" + `\${value}` + '</a>' : `\${value}`) : '&nbsp;',
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
                            html: values,
                            className: 'flex justify-center items-center h-full w-full',
                            style: 'border-color: var(--dtable-border-color)' + (isTotal ? '; background-color: var(--color-surface-light);' : '')
                        };
                    }
                    result.push(values);
                }
            }

            return result;
        }
        JS);
        return dtable
        (
            setID('designTable'),
            set::bordered(true),
            set::height(jsRaw("() => getHeight(800, $filterCount)")),
            set::cols($cols),
            set::data($data),
            set::emptyTip($emptyTip),
            set::rowHover(false),
            set::colHover(false),
            set::onRenderCell($onRenderCell),
            set::onCellClick($onCellClick),
            set::rowKey('ROW_ID'),
            set::plugins(array('header-group', 'cellspan')),
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
                console.log(options);
            }
            JS)),
            set::cellSpanOptions($cellSpan)
        );
    }

    protected function build()
    {
        global $lang;
        list($title, $class, $width) = $this->prop(array('title', 'class', 'width'));
        return div
        (
            setClass('dtable-content bg-canvas', $class),
            setStyle('width', $width),
            panel
            (
                set::title($title),
                set::shadow(false),
                set::bodyClass('pt-0 panel-body-height'),
                $this->buildFilters(),
                $this->buildDTable(),
                set::headingClass('h-12 border-b'),
                to::heading($this->block('heading'))
            )
        );
    }
}
