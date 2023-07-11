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
 * Submit data to product batch edit page by html form while click on the batch edit button.
 *
 * @param  object event
 * @access public
 * @return void
 */
window.onClickBatchEdit = function(event)
{
    const dtable = zui.DTable.query(event.target);

    const checkedList = dtable.$.getChecks();
    if(checkedList.length === 0) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('productIDList[]', id));

    postAndLoadPage($(event.target).parents('button').data('url'), postData);
};
