window.pivotID = typeof(pivotID) != 'undefined' ? pivotID : 0;
window.clickCell = function(col, {colName, rowInfo})
{
    const drillConditions = rowInfo.data.conditions[colName];
    const isDrill         = rowInfo.data.isDrill[colName];
    const version         = rowInfo.data.version;
    const status          = rowInfo.data.status;
    let value             = rowInfo.data[colName];

    if(!isDrill || rowInfo.data.field0_colspan) return false;

    let conditions   = [];
    let filterValues = {};
    let originField  = '0';
    let id           = window.pivotID;

    if(Array.isArray(value)) value = value[0];
    value = (value + '').replace('%', '');

    if(Array.isArray(drillConditions) && drillConditions.length)
    {
        [originField, conditions] = drillConditions;
        filterValues = getFilterValues();
        conditions   = conditions.map(condition => condition.value);
    }
    conditions   = latin1ToBase64(JSON.stringify(conditions))
    filterValues = latin1ToBase64(JSON.stringify(filterValues))

    let drillModalLink = $.createLink('pivot', 'drillModal', `pivotID=${id}&version=${version}&colName=${originField}&status=${status}&conditions=${conditions}&filterValues=${filterValues}&value=${value}`);
    drillModalLink = drillModalLink.replace(/\+/g, '%2B');

    zui.Modal.open({url: drillModalLink, size: 'lg', key: 'drill'});
}

window.latin1ToBase64 = function(str)
{
    const encoder = new TextEncoder();
    const latin1Array  = encoder.encode(str);
    const latin1String = String.fromCharCode.apply(null, latin1Array);
    return btoa(latin1String);
}

/**
 * 获得筛选器表单数据。
 * Get filter form data.
 *
 * @access public
 * @return object
 */
window.getFilterValues = function(where)
{
    let root = '';
    if(where == 'versions')
    {
        root = $('#pivotVersionPanel').find('#conditions .filter');
    }
    else
    {
        root = $('#conditions .filter');
    }

    const filterValues = {};
    root.each(function(index)
    {
        const $filter = $(this);
        if ($filter.hasClass('filter-input'))
        {
            filterValues[
                index] = $filter.find('input').val();
        }
        else if($filter.hasClass('filter-select') || $filter.hasClass('filter-multipleselect'))
        {
            const value = $filter.find('.pick-value').val();
            filterValues[index] = Array.isArray(value) ? value.reduce((obj, value, index) => ({...obj,[index]: value}), {}) : value;
        }
        else if($filter.hasClass('filter-date') || $filter.hasClass('filter-datetime'))
        {
            const $pickValue = $filter.find('.pick-value');
            if($pickValue.length == 1)
            {
                filterValues[index] = $pickValue.val();
            }
            else if($pickValue.length == 2)
            {
                filterValues[index] = {begin: $pickValue.eq(0).val(), end: $pickValue.eq(1).val()};
            }
        }
    });

    return filterValues;
}

/**
 * 计算数据表格的高度。
 * Calculate height of data table.
 *
 * @param  int    height
 * @access public
 * @return int
 */
window.getHeight = function(height = 800)
{
    const windowHeight = $(window).height();

    const $panelBody = $('#pivotPanel .panel-body');
    const styles = $panelBody.length ? window.getComputedStyle($panelBody[0]) : null;
    const paddingBottom = parseInt(styles?.paddingBottom ?? 0, 10);

    const boundingRect = $panelBody.length ? $panelBody[0].getBoundingClientRect() : null;
    const offsetTop = boundingRect?.y ?? 0;

    return Math.min(windowHeight - offsetTop - paddingBottom * 2 - 10, height);
}
