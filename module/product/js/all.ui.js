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

/**
 * 拖拽的用例或者场景是否允许放下。
 * Is it allowed to drop the dragged case or scene.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @access public
 * @return bool
 */
window.canSortTo = function(from, to)
{
    if(!from || !to) return false;
    if(from.data.program != to.data.program) return false;
    return true;
}

/**
 * 拖拽用例或者场景。
 * Drag case or scene.
 *
 * @param  from   被拿起的元素
 * @param  to     放下时的目标元素
 * @param  type   放在目标元素的上方还是下方
 * @access public
 * @return bool
 */
window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;
    if(from.data.program != to.data.program) return false;

    const url  = $.createLink('product', 'updateOrder');
    const form = new FormData();
    form.append('orderBy', orderBy);
    form.append('products', JSON.stringify(this.state.rowOrders));
    $.ajaxSubmit({url, data: form});

    return true;
}
