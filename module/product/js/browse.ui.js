$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this = $(this);
    if($this.attr('class').indexOf('disabled') !== -1) return;

    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));
    if($this.data('account')) postData.append('assignedTo', $this.data('account'));

    if($this.data('page') == 'batch')
    {
        postAndLoadPage($this.data('formaction'), postData);
    }
    else
    {
        $.ajaxSubmit({"url": $this.data('formaction'), "data": postData});
    }
});
$(document).off('click', '.batchImportToLibBtn').on('click', '.batchImportToLibBtn', function()
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    $("[name='storyIdList'").val(checkedList.join(','));
});

$(document).off('click', '.batchChangeParentBtn').on('click', '.batchChangeParentBtn', function(e)
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();

    $.cookie.set('checkedItem', checkedList.join(','), {expires:config.cookieLife, path:config.webRoot});
});

$(document).off('click', '.switchButton').on('click', '.switchButton', function()
{
    var storyViewType = $(this).attr('data-type');
    $.cookie.set('storyViewType', storyViewType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

$(document).off('click','#linkStoryByPlan button[type="submit"]').on('click', '#linkStoryByPlan button[type="submit"]', function()
{
    var planID = $('[name=plan]').val();
    if(planID)
    {
        $.ajaxSubmit({url: $.createLink('execution', 'importPlanStories', 'projectID=' + projectID + '&planID=' + planID)});
    }

    return false;
});

$(document).off('click', '.batchUnlinkStory').on('click', '.batchUnlinkStory', function(e)
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    /* 处理选中的子需求的ID，截取-后的子需求ID。*/
    /* Process selected child story ID. */
    for(i in checkedList)
    {
        const storyID = checkedList[i];
        if(storyID.includes('-')) checkedList[i] = storyID.slice(storyID.indexOf('-') + 1);
    }

    let batchUnlinkStoryURL = $.createLink('projectstory', 'batchUnlinkStory', 'projectID=' + projectID + '&stories=' + encodeURIComponent(checkedList.join(',')));
    $.get(batchUnlinkStoryURL, function(data)
    {
         try
         {
             data = JSON.parse(data);
             if(typeof data.result != 'undefined') return loadCurrentPage();
         }
         catch(error){}

         zui.Modal.open({id: 'batchUnlinkStoryBox'});
         $('#batchUnlinkStoryBox').html(data);
         $('#batchUnlinkStoryBox').off('mousedown', '.confirmBtn').on('mousedown', '.confirmBtn', function()
         {
             loadCurrentPage();
         });
    });
});

window.renderCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        const story = info.row.data;
        let html = '';
        if(story.parentName != undefined && story.parent > 0 && $.cookie.get('tab') == 'project' && projectHasProduct && vision != 'lite') html += story.parentName + ' / ';
        if(typeof modulePairs[story.rawModule] != 'undefined') html += "<span class='label gray-pale rounded-xl clip'>" + modulePairs[story.rawModule] + "</span> ";

        let gradeLabel = '';
        let showGrade  = false;
        const gradeMap = gradeGroup[story.type] || {};

        if(story.type != storyType) showGrade = true;
        if((story.type == 'epic' || story.type == 'requirement') && Object.keys(gradeMap).length >= 2) showGrade = true;
        if(story.type == 'story' && Object.keys(gradeMap).length >= 3) showGrade = true;
        if(story.grade > 1) showGrade  = true;
        if(tab != 'product') showGrade = true;

        if(showGrade) gradeLabel = gradeMap[story.grade];
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(story.color) result[0].props.style = 'color: ' + story.color;

        if((story.type == 'story' && !storyViewPriv) ||
           (story.type == 'requirement' && !requirementViewPriv) ||
           (story.type == 'epic' && !epicViewPriv)) result[0] = result[0].props.children;
        if(html) result.unshift({html});
    }
    if(info.col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
    }
    if(info.col.name == 'assignedTo' && info.row.data.status == 'closed')
    {
        delete result[0]['props']['data-toggle'];
        delete result[0]['props']['href'];
        result[0]['props']['className'] += ' disabled';
    }
    if(info.col.name == 'childItem')
    {
        result[1]['attrs']['title'] = info.row.data?.childItemTitle;
    }
    return result;
};

window.setStatistics = function(element, checkedIdList, pageSummary)
{
    if(checkedIdList == undefined || checkedIdList.length == 0) return {html: pageSummary};

    let total     = 0;
    let estimate  = 0;
    let rate      = '0%';
    let hasCase   = 0;
    let rateCount = checkedIdList.length;

    const rows  = element.layout.allRows;
    rows.forEach((row) => {
        if(checkedIdList.includes(row.id))
        {
            const story = row.data;
            if(story.type != storyType && tab == 'product') return;
            if(story?.needSummaryEstimate) estimate += parseFloat(story.estimate);
            if(story.caseCount > 0)
            {
                hasCase += 1;
            }
            else if(typeof story.isParent != 'undefined' && story.isParent == '1')
            {
                rateCount -= 1;
            }

            total += 1;
        }
    })

    if(rateCount) rate = Math.round(hasCase / rateCount * 10000) / 100 + '' + '%';

    return {html: checkedSummary.replace('%total%', total).replace('%estimate%', estimate.toFixed(2)).replace('%rate%', rate)};
};

window.setShowGrades = function()
{
    const showGrades = $('[name^=showGrades]').zui('picker').$.state.value;
    if(oldShowGrades == showGrades) return;

    const module = tab == 'product' || from == 'doc' ? storyType : tab;
    const link   = $.createLink('product', 'ajaxSetShowGrades', 'module=' + module + '&showGrades=' + showGrades);
    $.get(link, function()
    {
        if(from == 'doc')
        {
            const currentLink = $.createLink('product', 'browse', `productID=${productID}&branch=${branch}&browseType=${browseType}&param=${param}&storyType=${storyType}&orderBy=${orderBy}&recTotal=${recTotal}&recPerPage=${recPerPage}&pageID=${pageID}&projectID=${projectID}&from=doc&blockID=${blockID}`);
            loadModal(currentLink);
        }
        else
        {
            loadCurrentPage();
        }
    });
}

window.importToLib = function()
{
    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    let storyIdList = '';
    checkedList.forEach((id) => storyIdList += id + ',');
    $('#storyIdList').val(storyIdList);
}

window.checkedChange = function(changes)
{
    if(!this._checkedRows) this._checkedRows = {};
    Object.keys(changes).forEach((rowID) =>
    {
        const row = this.getRowInfo(rowID);
        if(row !== undefined) this._checkedRows[rowID] = row.data;
    });
}

window.insertListToDoc = function()
{
    const dtable      = zui.DTable.query($('#stories'));
    const myTable     = dtable.$;
    const checkedList = Object.keys(myTable.state.checkedRows);
    if(!checkedList.length) return;

    let {cols} = dtable.options;
    const data = checkedList.map(rowID => myTable._checkedRows[rowID]);
    const docID = getDocApp()?.docID;

    let blockType = 'productStory';
    if(storyType == 'epic')        blockType = 'ER';
    if(storyType == 'requirement') blockType = 'UR';

    const url = $.createLink('doc', 'buildZentaoList', `docID=${docID}&type=${blockType}&blockID=${blockID}`);
    const formData = new FormData();
    formData.append('cols', JSON.stringify(cols));
    formData.append('data', JSON.stringify(data));
    formData.append('idList', checkedList.join(','));
    formData.append('url', insertListLink);
    $.post(url, formData, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.result == 'success')
        {
            const oldBlockID = resp.oldBlockID;
            const newBlockID = resp.newBlockID;
            zui.Modal.hide();
            window.insertZentaoList && window.insertZentaoList(blockType, newBlockID, null, oldBlockID);
        }
    });
}

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#stories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
