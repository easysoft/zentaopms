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

    if(col.name == 'name' && row.data.type !== 'product')
    {
        /* Remove class of checkbox. */
        result.splice(result.length - 1);

        /* Remove checkbox. */
        result.forEach(function(ele, idx)
        {
            if(!ele.props.class) return;
            if(ele.props.class.includes('checkbox')) result.splice(idx, 1);
        });

        result[result.length - 1] = row.data.name;

        return result;
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
onClickBatchEdit = function(event)
{
    event.stopPropagation();
    event.preventDefault();

    /* Get checked product ID list. */
    const dtable      = zui.DTable.query(event.target);
    const checkedList = dtable.$.getChecks();

    if(checkedList.length === 0) return;

    /* Create form. */
    const f = document.createElement("form");
    f.action = $(event.target).attr('href');
    f.method = "POST";
    f.target = "_self";

    /* Create element to carry data. */
    checkedList.forEach(function(id)
    {
        if(id.includes('-')) return;

        const item = document.createElement('input');
        item.name  = 'productIDList[]';
        item.value = id;

        f.appendChild(item);
    });

    /* Append form to body. */
    document.body.appendChild(f);

    f.submit();
}

onClickCheckBatchEdit = function(event)
{
    $.cookie.set('checkedEditProduct', 1);
    loadCurrentPage(["table/#dtable:type=json&data=props"]);
}

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
}
