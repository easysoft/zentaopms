window.footerGenerator = function()
{
    const count = this.layout.allRows.filter((x) => x.data.type === "product").length;
    const statistic = summeryTpl.replace('%s', ' ' + count + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'name')
    {
        if(row.data.postponed) result[result.length] = {html:'<span class="label size-sm circle danger-pale">' + langPostponed + '</span>', className:'flex items-end w-full', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'budget')
    {
        result[0] = {html: '<div>' + row.data.budget + ' <span class="icon icon-exclamation-sign mr-2 text-danger"></span></div>', className:'flex items-end w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    if(col.name === 'invested')
    {
        result[0] = {html: '<div>' + row.data.invested + ' <small class="text-gray">' + langManDay + '</small></div>', className:'flex items-end w-full items-end', style:{flexDirection:"column"}};
        return result;
    }

    return result;
}
