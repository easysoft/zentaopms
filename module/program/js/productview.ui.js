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
