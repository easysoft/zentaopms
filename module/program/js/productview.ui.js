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

    if(col.name == 'totalProjects' && row.data.type !== 'product')
    {
        return [row.data.totalProjects];
    }

    return result;
}

window.iconRenderProductView = function(value, row)
{
    if(row.data.type === 'program')     return {className: 'icon icon-cards-view text-gray'};
    if(row.data.type === 'productLine') return {className: 'icon icon-lane text-gray'};

    return '';
}

/**
 * Submit data to product batch edit page by html form while click on the batch edit button.
 *
 * @param  object event
 * @access public
 * @return void
 */
window.onClickBatchEdit = function(event)
{
    const dtable      = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();

    if(!checkedList.length) return;

    const formData = new FormData();
    checkedList.forEach(function(id)
    {
        formData.append('productIDList[]', id);
    });

    postAndLoadPage($(event.target.closest('button')).data('url'), formData, '', {app: 'product'});
};

onClickCheckBatchEdit = function(event)
{
    $.cookie.set('checkedEditProduct', 1);
    loadCurrentPage(["table/#dtable:type=json&data=props"]);
};

window.footerSummary = function(checkedIdList)
{
    if(!checkedIdList.length)
    {
        return {html: pageSummary, className: 'text-dark'};
    }

    let totalProducts = 0;
    checkedIdList.forEach(function(id)
    {
        if(id.includes('-')) return;
        totalProducts++;
    });

    var summary = checkedSummary.replace('%total%', totalProducts);

    return {html: summary};
};
