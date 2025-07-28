window.unlinkObject = function(objectType, objectID)
{
    zui.Modal.confirm({message: confirmLang[objectType], icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({'url': unlinkURL[objectType].replace('%s', objectID)});
    });
};

window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        let gradeLabel = gradeGroup[story.type][story.grade];
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }

    if(info.col.name == 'sort')
    {
        result[0] = {html: "<i class='icon-move'></i>", className: 'text-gray cursor-move move-plan'};
    }
    return result;
};

window.ajaxConfirmLoad = function(obj)
{
    var $this   = $(obj);
    var action = $this.data('action');
    zui.Modal.confirm({message: confirmLang[action], icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.get($this.data('url'), function(){loadCurrentPage()});
    });
};

window.showLink = function(type, params, onlyUpdateTable)
{
    const url = $.createLink('productplan', type === 'story' ? 'linkStory' : 'linkBug', 'planID=' + planID + (params || '&browseType=&param='));
    if(onlyUpdateTable)
    {
        loadComponent($('#' + (type === 'story' ? 'stories' : 'bugs')).find('.dtable').attr('id'), {url: url, component: 'dtable', partial: true});
        return;
    }
    loadTarget({url: url, target: type === 'story' ? 'stories' : 'bugs'});
    $('.detail-header .open-url').attr('href', $.createLink('productplan', 'view', 'planID=' + planID + '&type=' + type)).removeAttr('data-back');
};

window.onSearchLinks = function(type, result)
{
    const params = $.parseLink(result.load).vars[4];
    showLink(type, params ? atob(params[1]) : null, true);
    return false;
};

/**
 * 计算表格信息的统计。
 * Set summary for table footer.
 *
 * @param  element element
 * @param  array   checkedIdList
 * @access public
 * @return object
 */
window.setStatistics = function(element, checkedIdList, pageSummary)
{
    if(checkedIdList == undefined || checkedIdList.length == 0) return {html: pageSummary};

    let checkedEstimate = 0;
    let checkedCase     = 0;
    let total           = 0;

    const rows = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIdList.includes(row.id))
        {
            const story = element.getRowInfo(row.id);
            if(story.data.type == 'story')
            {
                total += 1;
                if(story.data.isParent == '0') checkedEstimate += parseFloat(story.data.estimate);
                if(cases[row.id]) checkedCase += 1;
            }
        }
    });

    const rate = total == 0 ? '0%' : Math.round(checkedCase / total * 10000) / 100 + '' + '%';
    return {html: checkedSummary.replace('%total%', total)
        .replace('%estimate%', checkedEstimate.toFixed(1))
        .replace('%rate%', rate)};
}

$(document).off('click', '.batch-btn > a, .batch-btn').on('click', '.batch-btn > a, .batch-btn', function()
{
    const $this  = $(this);
    const type   = $this.data('type');
    const dtable = zui.DTable.query($('#' + type + 'DTable'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    const url      = $this.data('url');
    checkedList.forEach((id) => postData.append(type + 'IdList[]', id));
    if($this.data('account')) postData.append('assignedTo', $this.data('account'));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage(url, postData);
    }
    else
    {
        $.ajaxSubmit({url: url, data: postData});
    }
});

window.handleLinkObjectClick = function($this)
{
    const type   = $this.data('type');
    const dtable = zui.DTable.query($this);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postKey  = type == 'story' ? 'stories' : 'bugs';
    const postData = new FormData();
    checkedList.forEach((id) => postData.append(postKey + '[]', id));

    $.ajaxSubmit({url: $this.data('url'), data: postData});
};

$(function()
{
    if(initLink == 'true') window.showLink(type, linkParams);
});

window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    const url  = $.createLink('productplan', 'ajaxStorySort', `planID=${planID}`);
    const form = new FormData();

    form.append('storyIdList', JSON.stringify(this.state.rowOrders));
    form.append('orderBy',     orderBy);
    form.append('pageID',      storyPageID);
    form.append('recPerPage',  storyRecPerPage);

    $.ajaxSubmit({url, data:form});
    $.apps.updateAppUrl($.createLink('productplan', 'view', `planID=${planID}&type=story&orderBy=order_desc`));
    return true;
}
