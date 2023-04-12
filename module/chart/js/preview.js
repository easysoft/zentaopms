$(function()
{
    initCheckBox();
    initQueryBtn();
    $('#exportchart').modalTrigger();

    chartMap = new Map();
    if(charts.length > 0)
    {
        var chartPros = charts.map(function(chart)
        {
            var echartDom = $('#chartDraw' + chart.currentGroup + chart.id).get(0);
            var echart = echarts.init(echartDom);
            ajaxGetChart(false, chart, echart);
            chartMap.set(chart.cudrrentGroup + chart.id, chart);
            return renderFilters(chart);
        });

        Promise.all(chartPros).then(function()
        {
            console.log(11);
            calcPreviewGrowFilter();
            $('body').resize(() => {calcPreviewGrowFilter(true);});
        });

    }


    $('[data-toggle="tooltip"]').tooltip();
})

/**
 * Init check box.
 *
 * @access public
 * @return void
 */
function initCheckBox()
{
    /* Use parent checked prop set all child. */
    $('input[data-type="parent"]').click(function()
    {
        $(this).parent().siblings('ul').find('input[data-type="child"]').prop('checked', $(this).prop('checked'));
    });

    $('input[data-type="child"]').click(function()
    {
        /* If child unchecked after click, set parent unchecked. */
        if(!$(this).prop('checked'))
        {
            $(this).parentsUntil('#chartGroups', 'li').find('div').children('input[data-type="parent"]').prop('checked', false);
        }
        /* If child checked after click, if all child checked, set parent checked. */
        else
        {
            if($(this).closest('ul').find('input[data-type="child"]').length >= 1)
            {
                var siblings_checked = $(this).closest('ul').find('input[data-type="child"]:checked').length;
                var siblings_total   = $(this).closest('ul').find('input[data-type="child"]').length;
                if(siblings_checked == siblings_total)
                {
                    $(this).parentsUntil('#chartGroups', 'li').find('div').children('input[data-type="parent"]').prop('checked', true);
                }
            }
        }
    });

    /* Use click to trigger event. */
    charts.forEach(function(item)
    {
        $("#chart_" + item.currentGroup + '_' + item.id).click();
    });
}

/**
 * Init query button.
 *
 * @access public
 * @return void
 */
function initQueryBtn()
{
    $('.btn-query').click(function()
    {
        var chartID   = $(this).attr('data-chart');
        var chartInfo = chartMap.get(chartID);
        chartInfo.searchFilters = JSON.parse(JSON.stringify(chartInfo.filters));

        // Foreach filters and set current value in searchFilters. */
        $('#filterItems' + chartID + ' .filter-items').children().each(function(index)
        {
            var $child = $(this);
            var filter = chartInfo.searchFilters[index];
            var type   = filter.type;
            var value  = null;

            if(type == 'input' || type == 'select')
            {
                var value = $child.find('#default').val();
                chartInfo.searchFilters[index].default = value;
            }
            else if(type == 'date' || type == 'datetime')
            {
                var begin = $child.find('input').first().val();
                var end   = $child.find('input').last().val();

                var value = {"begin":begin, "end":end};
                chartInfo.searchFilters[index].default = value;
            }
            else if(type == 'condition')
            {
                var operator = $child.find('#operator').data('zui.picker').getValue();
                var value    = $child.find('#value').val();

                chartInfo.searchFilters[index].operator = operator;
                chartInfo.searchFilters[index].value    = value;
            }
        });

        //* Reload echart. */
        var echartDom  = $('#chartDraw' + chartID).get(0);
        var echartInfo = echarts.init(echartDom);
        ajaxGetChart(false, chartInfo, echartInfo);
    });
}

function calcPreviewGrowFilter(resize = false)
{
    if(charts.length <= 0) return;
    charts.forEach(function(chart)
    {
        /* When body resize, resize echarts. */
        if(resize)
        {
            var echartDom = $('#chartDraw' + chart.currentGroup + chart.id).get(0);
            var echart = echarts.init(echartDom);
            echart.resize();
        }

        var $filterItems = $('#filterItems' + chart.currentGroup + chart.id + ' .filter-items');
        /* When refreshing this page, ZenTao load this page twice, when the first load isn't complete, jump back to index and load the second time,
           the first load can't calc filter width, can't get the element using jQuery at first load. */
        var hasInit = true;
        chart.filters.forEach(function(filter, index)
        {
            var nowItem    = '.filter-item-' + index;
            var $titleSpan = $filterItems.find(nowItem).find('.input-group-addon').first();
            if(!$titleSpan.length) hasInit = false;
        });
        if(!hasInit) return;

        var domWidth     = $filterItems[0].getBoundingClientRect().width;
        var nowWidth     = domWidth;
        var nowCount     = 0;
        var canGrowTotal = 0;
        chart.filters.forEach(function(filter, index)
        {
            var nowItem      = '.filter-item-' + index;
            var leftPadding  = parseInt($filterItems.find(nowItem).css('padding-left'));
            var rightPadding = parseInt($filterItems.find(nowItem).css('padding-right'));
            var spanWidth    = $filterItems.find(nowItem).find('.input-group-addon').first()[0].getBoundingClientRect().width;
            var filterWidth  = ((filter.type == 'input' || filter.type == 'select') ? WIDTH_INPUT : WIDTH_DATE) + (spanWidth + leftPadding + rightPadding);

            /* Clear the flex-basis and set flex-basic again. */
            $filterItems.find(nowItem).css('flex-basis', '');
            $filterItems.find(nowItem).css('flex-basis', filterWidth);
            if(nowWidth - filterWidth >= 0)
            {
                nowWidth -= filterWidth;
                nowCount ++;
            }
            else
            {
                canGrowTotal += nowCount;
                nowWidth = domWidth - filterWidth;
                nowCount = 1;
            }
        });

        /* Clear all filter filter-item-grow and add class to support grow element. */
        $filterItems.children().removeClass('filter-item-grow');
        chart.filters.forEach(function(filter, index)
        {
            var nowItem = '.filter-item-' + index;
            if(canGrowTotal >= index + 1) $filterItems.find(nowItem).addClass('filter-item-grow');
        });
    });
}

function renderFilters(chart)
{
    return new Promise(function(resolve, reject)
    {
        if(chart.filters.length == 0)
        {
            resolve();
            return;
        }

        var fieldNames = {};
        Object.keys(chart.fieldSettings).forEach(function(key){fieldNames[key] = chart.fieldSettings[key].name;});
        $.post(createLink('chart', 'ajaxGetFilterForm', 'chartID=' + chart.id), {fieldList: fieldNames, fieldSettings: chart.fieldSettings, filters: chart.filters, langs: chart.langs}, function(resp)
        {
            resp = JSON.parse(resp);
            chart.filters.forEach(function(filter, index)
            {
                var $filterItems = $('#filterItems' + chart.currentGroup + chart.id + ' .filter-items');
                $filterItems.append(renderFilterItem(filter, resp, index));
            });
            resolve();
        });
    })
}

function renderFilterItem(filter, resp, index, step)
{
    var tpl = $('#filterItemTpl').html();
    var data =
    {
        index: index,
        name: filter.name,
        search: resp[index].item
    };
    var html = $($.zui.formatString(tpl, data))
    initPicker(html);
    initDatepicker(html);
    return html;
}
