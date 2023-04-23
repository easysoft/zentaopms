window.footerGenerator = function()
{
    const count = this.layout.allRows.filter((x) => x.data.type === "product").length;
    const statistic = langSummary.replace('%s', ' ' + count + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(!col || !row || col.name !== 'releases') return result;

    var changed = row.data.releases - row.data.releasesOld;

    if(changed === 0) result[0] = 0;
    if(changed > 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>'};
    if(changed < 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>'};

    return result;
}

window.programMenuOnClick = function(data, url)
{
    location.href = url.replace('%d', data.item.key);
}
