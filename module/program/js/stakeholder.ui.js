window.footerGenerator = function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();

    const statistic = summeryTpl.replace('%s', ' ' + checkedList.length + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

