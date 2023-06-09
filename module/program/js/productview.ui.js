window.footerGenerator = function()
{
    return [{children: summary, className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name !== 'releaseCount') return result;

    var changed = row.data.releaseCount - row.data.releaseCountOld;

    if(changed === 0) result[0] = 0;
    if(changed > 0)   result[0] = row.data.releaseCount + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>';
    if(changed < 0)   result[0] = row.data.releaseCount + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>';

    return result;
}
