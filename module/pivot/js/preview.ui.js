/**
 * 查询条件改变时重新加载 Bug 创建表。
 * Reload bug create table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadBugCreate()
{
    const begin     = $('#conditions').find('[name="begin"]').val().replaceAll('-', '');
    const end       = $('#conditions').find('[name="end"]').val().replaceAll('-', '');
    const product   = $('#conditions').find('[name=product]').val();
    const execution = $('#conditions').find('[name=execution]').val();
    const params    = window.btoa('begin=' + begin + '&end=' + end + '&product=' + product + '&execution=' + execution);
    const link      = $.createLink('pivot', 'preview', 'dimensionID=' + dimensionID + '&groupID=' + groupID + '&method=bugCreate&params=' + params);
    loadPage(link, '#table-pivot-preview');
}

/**
 * 查询条件改变时重新加载产品汇总表。
 * Reload product summary table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadProductSummary()
{
    let conditions = '';
    $('#conditions input[type=checkbox]').each(function(i)
    {
        if($(this).prop('checked')) conditions += $(this).val() + ',';
    })
    conditions = conditions.substring(0, conditions.length - 1);

    const params = window.btoa('conditions=' + conditions);
    const link   = $.createLink('pivot', 'preview', 'dimensionID=' + dimensionID + '&groupID=' + groupID + '&method=productSummary&params=' + params);
    loadPage(link, '#table-pivot-preview');
}

/**
 * 查询条件改变时重新加载执行偏差表。
 * Reload execution deviation table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadProjectDeviation()
{
    const begin  = $('#conditions').find('[name="begin"]').val().replaceAll('-', '');
    const end    = $('#conditions').find('[name="end"]').val().replaceAll('-', '');
    const params = window.btoa('begin=' + begin + '&end=' + end);
    const link   = $.createLink('pivot', 'preview', 'dimensionID=' + dimensionID + '&groupID=' + groupID + '&method=projectdeviation&params=' + params);
    loadPage(link, '#table-pivot-preview,#pivotChart');
}

/**
 * 查询条件改变时重新加载工作负载表。
 * Reload workload table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadWorkload()
{
    const begin    = $('#conditions').find('[name="begin"]').val();
    const end      = $('#conditions').find('[name="end"]').val();
    const workhour = $('#conditions').find('#workhour').val();
    const dept     = $('#conditions').find('[name=dept]').val();
    const assign   = $('#conditions').find('[name=assign]').val();
    const days     = diffDate(begin, end);

    $('#days').val(days);

    const params = window.btoa('begin=' + begin.replaceAll('-', '') + '&end=' + end.replaceAll('-', '') + '&days=' + days + '&workhour=' + workhour + '&dept=' + dept + '&assign=' + assign);
    const link   = $.createLink('pivot', 'preview', 'dimensionID=' + dimensionID + '&groupID=' + groupID + '&method=workload&params=' + params);
    loadPage(link, '#table-pivot-preview');
}

/**
 * 查询条件改变时重新加载自定义透视表。
 * Reload custom pivot table when query conditions changed.
 *
 * @access public
 * @return void
 */
function loadCustomPivot()
{
    const form = new FormData();
    $('#conditions .filter').each(function(index)
    {
        const $filter = $(this);
        if ($filter.hasClass('filter-input'))
        {
            form.append('filterValues[' + index + ']', $filter.find('input').val());
        }
        else if($filter.hasClass('filter-select'))
        {
            const value = $filter.find('.pick-value').val();
            if(Array.isArray(value))
            {
                value.filter(Boolean).forEach((item) => form.append('filterValues[' + index + '][]', item));
            }
            else
            {
                form.append('filterValues[' + index + ']', value);
            }
        }
        else if($filter.hasClass('filter-date') || $filter.hasClass('filter-datetime'))
        {
            const $pickValue = $filter.find('.pick-value');
            if($pickValue.length == 1)
            {
                form.append('filterValues[' + index + ']', $pickValue.val());
            }
            else if($pickValue.length == 2)
            {
                form.append('filterValues[' + index + '][begin]', $pickValue.eq(0).val());
                form.append('filterValues[' + index + '][end]', $pickValue.eq(1).val());
            }
        }
    });

    const params = window.btoa('groupID=' + currentGroup + '&pivotID=' + pivotID);
    const link   = $.createLink('pivot', 'preview', 'dimensionID=' + dimensionID + '&groupID=' + groupID + '&method=show&params=' + params);
    postAndLoadPage(link, form, '#table-pivot-preview');
}

/**
 * 把日期字符串转换成日期对象。
 * Convert date string to date object.
 *
 * @param  string $dateString
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * 计算两个日期之间的天数。
 * Compute the days between two date.
 *
 * @param  string $date1
 * @param  string $date1
 * @access public
 * @return int
 */
function diffDate(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

/**
 * 如果列包含切片字段，重新渲染单元格。
 * If column contains slice field, re-render cell.
 *
 * @param  array  result
 * @param  object cell
 * @access public
 * @return array
 */
renderCell = function(result, {row, col})
{
    if(result && col.setting.colspan)
    {
        const values = result.shift();
        result.push({className: 'gap-0 px-0'});
        values.forEach((value, index) => result.push({html: value ? value : '&nbsp;', className: 'flex justify-center items-center h-full w-1/2' + (index == 0 ? ' border-r': ''), style: 'border-color: var(--dtable-border-color)'}));
    }

    return result;
}

/**
 * 合并单元格。
 * Merge cell.
 *
 * @param  object cell
 * @access public
 * @return object|void
 */
getCellSpan = function(cell)
{
    const options = this.options.cellSpanOptions[cell.col.name];
    if(options)
    {
        const rowSpan = cell.row.data[options.rowspan ?? 'rowspan'] ?? 1;
        const colSpan = cell.row.data[options.colspan ?? 'colspan'] ?? 1;
        return {rowSpan, colSpan};
    }
}

/**
 * 计算数据表格的高度。
 * Calculate height of data table.
 *
 * @param  int    height
 * @access public
 * @return int
 */
getHeight = function(height)
{
    const windowHeight       = $(window).height();
    const headerHeight       = $('#header').outerHeight();
    const menuHeight         = $('#mainMenu').outerHeight();
    const parentHeight       = $('#pivotPanel').parent().outerHeight();
    const conditionHeight    = $('#conditions').length == 1 ? $('#conditions').outerHeight(true) : 0;
    const panelHeight        = $('#pivotPanel').outerHeight(true);
    const panelHeadingHeight = $('#pivotPanel .panel-heading').outerHeight();
    const panelBodyPaddingY  = $('#pivotPanel .panel-body').innerHeight() - $('#pivotPanel .panel-body').height();
    const parentGapHeight    = parentHeight - conditionHeight - panelHeight;

    return Math.min(windowHeight - headerHeight - menuHeight - conditionHeight - parentGapHeight - panelHeadingHeight - panelBodyPaddingY, height);
}
