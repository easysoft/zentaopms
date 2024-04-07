window.footerGenerator = function()
{
    const count = this.layout.allRows.filter((x) => x.data.type === "product").length;
    const statistic = langSummary.replace('%s', ' ' + count + ' ');
    return [{children: statistic, className: "text-dark"}, "flex", "pager"];
};

window.renderReleaseCountCell = function(result, {col, row})
{
    if(!col || !row || col.name !== 'releases') return result;

    var changed = row.data.releases - row.data.releasesOld;

    if(changed === 0) result[0] = 0;
    if(changed > 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>'};
    if(changed < 0)   result[0] = {html: row.data.releases + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>'};

    return result;
};

window.programMenuOnClick = function(data, url)
{
    loadPage(url.replace('%d', data.item.key));
};


/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    $('#productListForm [name^=productIDList]:checked').each(function(index, ele)
    {
        checkedItems.push($(ele).val());
    });
    return checkedItems;
};

window.onRenderHeaderCell = function(result, {col, row})
{
    const storyGroup = ['epic', 'requirement', 'story'];
    for(const storyType of storyGroup)
    {
        if(storyCountGroup.hasOwnProperty(storyType) && storyCountGroup[storyType].includes(col.name))
        {
            const hasLink = typeof result[0] === 'object';
            const field   = hasLink ?
                `<a href=${result[0].props['href']} class='${result[0].props['className']} text-current' data-load=${result[0].props['data-load']}>
                   ${result[0].props.children[0]}
                   <div class='dtable-sort dtable-sort-none'></div>
                 </a>` : result[0];

            /* 保持父标题居中。 */
            const index      = storyCountGroup[storyType].length % 2 === 1 ? Math.floor(storyCountGroup[storyType].length / 2) : storyCountGroup[storyType].length / 2;
            const commonName = col.name === storyCountGroup[storyType][index] ? storyTypeLang[storyType] : '';

            const headerContent =
                `<div class='col w-full h-full items-center justify-between'>
                   <div class="h-1/2">
                     ${commonName}
                   </div>
                   <hr class='w-full'>
                   <div class="h-1/2">
                     ${field}
                   </div>
                 </div>`;

            result[0] = {html: headerContent};
            $.extend(result[0], {className: 'w-full h-full'});
        }
    }

    return result;
}
