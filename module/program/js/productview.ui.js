window.footerGenerator = function()
{
    return [{children: summary, className: "text-dark"}, "flex", "pager"];
}

window.renderCellProductView = function(result, {col, row})
{
    if(col.name === 'createdDate')
    {
        if(row.data.createdDate === '') return [''];
    }

    if(col.name === 'latestReleaseDate')
    {
        if(row.data.latestReleaseDate === '') return [''];
    }

    if(col.name == 'totalProjects' && row.data.type !== 'product') return [row.data.totalProjects];
    return result;
}

window.iconRenderProductView = function(value, row)
{
    if(row.data.type === 'program')     return {className: 'icon icon-cards-view text-gray'};
    if(row.data.type === 'productLine') return {className: 'icon icon-lane text-gray'};

    return '';
}

window.footerSummary = function(checkedIdList, pageSummary)
{
    if(!checkedIdList.length) return {html: pageSummary, className: 'text-dark'};

    let totalProducts = 0;
    checkedIdList.forEach(function(id)
    {
        if(id.includes('-')) return;
        totalProducts++;
    });

    var summary = checkedSummary.replace('%total%', totalProducts);

    return {html: summary};
};

$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#productviews'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('productIDList[]', id));

    if($this.data('page') == 'batch') postAndLoadPage($this.data('formaction'), postData);
});

/**
 * 拖拽的项目集或者产品是否允许放下。
 * Is it allowed to drop the dragged program or project.
 *
 * @param  from   拖动的行信息
 * @param  to     被拖动到的行信息
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    if(from.data.parent != to.data.parent) return false;
    if(from.data.type != to.data.type) return false;
    return true;
};

/**
 * 拖拽项目集或产品。
 * Drag program or project.
 *
 * @param  from   拖动的行信息
 * @param  to     被拖动到的行信息
 * @access public
 * @return bool
 */
window.onSortEnd = function(from, to)
{
    if(!canSortTo(from, to)) return false;

    const programIdList = [];
    const productIdList = [];
    const orders        = this.state.rowOrders;
    for(id in orders)
    {
        if(id.includes('program'))
        {
            programID = id.slice(id.indexOf('-') + 1);
            programIdList[programID] = orders[id];
        }
        else
        {
            productIdList[id] = orders[id];
        }
    }

    const fromType = from.data.type;
    const url      = $.createLink(fromType, 'updateOrder');
    const form     = new FormData();
    if(fromType == 'program') form.append('programIdList', JSON.stringify(programIdList));
    if(fromType == 'product')
    {
        form.append('orderBy', 'order_asc');
        form.append('products', JSON.stringify(productIdList));
    }
    $.ajaxSubmit({url, data:form});

    return true;
};
