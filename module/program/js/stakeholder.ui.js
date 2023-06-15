window.footerGenerator = function()
{
    var checkedCount = $("div.is-checked").length;
    const statistic = summeryTpl.replace('%s', ' ' + checkedCount + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

