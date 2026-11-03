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

$(document).off('click', '.batchSubmitReviewBtn').on('click', '.batchSubmitReviewBtn', function(e)
{
    e.preventDefault();

    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const storyIdList   = checkedList.join(',');
    const storyModule   = typeof tab !== 'undefined' && tab === 'project' ? 'projectstory' : storyType || 'story';
    const checkLink     = $.createLink('story', 'ajaxCheckBatchSubmitReview', 'productID=' + productID + '&storyType=' + storyType + '&storyIdList=' + storyIdList);

    $.getJSON(checkLink, function(data)
    {
        if(data.result != 'success') return;

        const hasMessage = data.message;
        const hasValid   = data.hasValid;
        const allowedIds = data.allowedStoryIdList;

        const openModal = function(idList)
        {
            loadModal($.createLink(storyModule, 'batchSubmitReview', 'productID=' + productID + '&storyType=' + storyType + '&storyIdList=' + idList));
        };

        if(hasMessage && hasValid && allowedIds && allowedIds.length)
        {
            zui.Modal.confirm({message: hasMessage}).then(function(res)
            {
                if(res) openModal(allowedIds.join(','));
            });
        }
        else if(hasMessage)
        {
            zui.Modal.alert(hasMessage);
        }
        else
        {
            openModal(storyIdList);
        }
    });
});
$(document).off('click', '.batchChangeParentBtn').on('click', '.batchChangeParentBtn', function(e)
{
    e.preventDefault();

    const dtable      = zui.DTable.query($('#stories'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const postData = new FormData();
    checkedList.forEach((id) => postData.append('storyIdList[]', id));

    zui.Modal.open({
        type: 'ajax',
        request: {
            url: $.createLink(storyType, 'batchChangeParent', 'productID=' + productID + '&storyType=' + storyType),
            method: 'post',
            data: postData
        }
    });
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
        if(story.color)
        {
            if(result[0].props !== undefined)
            {
                result[0].props.style = 'color: ' + story.color;
            }
            else
            {
                result[2]['style']['color'] = story.color;
            }
        }

        if(result[0].props && ((story.type == 'story' && !storyViewPriv) || (story.type == 'requirement' && !requirementViewPriv) || (story.type == 'epic' && !epicViewPriv))) result[0] = result[0].props.children;

        if(html) result.unshift({html});
    }
    if(info.col.name == 'status' && result)
    {
        result[0] = {html: `<span class='status-${info.row.data.rawStatus}'>` + info.row.data.status + "</span>"};
    }
    if(info.col.name == 'assignedTo')
    {
        if(info.row.data.rawStatus == 'closed' && result[0]['props'])
        {
            delete result[0]['props']['data-toggle'];
            delete result[0]['props']['href'];
            result[0]['props']['className'] += ' disabled';
        }
        else
        {
            let assignToClass = info.row.data.assignedTo == userAccount ? 'is-me' : '';
            if(!info.row.data.assignedTo) assignToClass = 'is-unassigned';

            if((info.row.data.type == 'epic' && epicAssignedToPriv) || (info.row.data.type == 'requirement' && requirementAssignedToPriv) || (info.row.data.type == 'story' && storyAssignedToPriv))
            {
                if(hasCurrentPageAssignPriv) result[0]['props']['href'] = $.createLink(info.row.data.type, 'assignTo', 'storyID=' + info.row.data.id);
                if(!hasCurrentPageAssignPriv) result[0] = {html : '<a href=' + $.createLink(info.row.data.type, 'assignTo', 'storyID=' + info.row.data.id) + ' data-toggle="modal" class="dtable-assign-btn ' + assignToClass + '"><i class="icon icon-hand-right"></i><span>' + result[0] + '</span></a>'}
            }

            if((info.row.data.type == 'epic' && !epicAssignedToPriv) || (info.row.data.type == 'requirement' && !requirementAssignedToPriv) || (info.row.data.type == 'story' && !storyAssignedToPriv))
            {
                result[0] = {html : info.row.data.assignedToName};
            }
        }
    }
    if(info.col.name == 'childItem')
    {
        result[1]['attrs']['title'] = info.row.data?.childItemTitle;
    }
    if(info.col.name == 'taskCount' && !info.row.data.taskCount)
    {
        if(result[0]['type']) result[0]['type'] = 'text';
        if(result[0]['props'])
        {
            delete result[0]['props']['data-toggle'];
            delete result[0]['props']['href'];
        }
    }
    if(info.col.name == 'bugCount' && !info.row.data.bugCount)
    {
        if(result[0]['type']) result[0]['type'] = 'text';
        if(result[0]['props'])
        {
            delete result[0]['props']['data-toggle'];
            delete result[0]['props']['href'];
        }
    }
    if(info.col.name == 'caseCount' && !info.row.data.caseCount)
    {
        if(result[0]['type']) result[0]['type'] = 'text';
        if(result[0]['props'])
        {
            delete result[0]['props']['data-toggle'];
            delete result[0]['props']['href'];
        }
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
    /*
      TODO【项目需求列表-用例覆盖率-仅统计研发需求】
      现状：项目需求列表（tab == 'project'）的数据由 productZen::getStories 以 storyType='all' 查询，
            列表中会同时出现 epic、requirement、story 三种类型。当前 setStatistics 计算选中项汇总时，
            只在产品页（tab == 'product'）按 storyType 过滤类型，项目页不做过滤，
            导致用例覆盖率的分子 hasCase 与分母 rateCount 把业务需求、用户需求也算进去，
            与页面初始汇总（productModel::summary() 已只统计 type == 'story'）口径不一致。
      设计（伪代码）：
        let rateCount = 0;                                  // 不再以选中总数初始化
        rows.forEach((row) => {
            if(!checkedIdList.includes(row.id)) return;
            const story = row.data;
            if(story.type != storyType && tab == 'product') return;   // 保持现有行为
            if(story?.needSummaryEstimate) estimate += parseFloat(story.estimate); // 保持现有行为
            total += 1;                                     // 保持现有行为：选中总数仍统计所有类型

            if(story.type != 'story') return;               // 新增：用例覆盖率只统计研发需求
            rateCount += 1;                                 // 保持现有行为：非父需求计入分母
            if(story.caseCount > 0)
            {
                hasCase += 1;                               // 保持现有行为：有用例的需求计入分子
            }
            else if(typeof story.isParent != 'undefined' && story.isParent == '1')
            {
                rateCount -= 1;                             // 保持现有行为：无用例的父需求不计入分母
            }
        });

        if(rateCount) rate = Math.round(hasCase / rateCount * 10000) / 100 + '' + '%';
      备注：
        - 页面初始汇总由 productModel::summary($stories, 'all') 计算，已通过
          if($story->type != 'story') continue 过滤，本次无需改动服务端。
        - 选中总数 total 与预计工时 estimate 保持统计全部类型，不改变现有展示口径。
        - 执行需求列表（module/execution/js/story.ui.js）是另一套实现，如也需统一口径请另行确认。
    */
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

    const module = tab == 'product' || from == 'doc' || from == 'ai' ? storyType : tab;
    const link   = $.createLink('product', 'ajaxSetShowGrades', 'module=' + module + '&showGrades=' + showGrades);
    $.get(link, function()
    {
        if(from == 'doc' || from == 'ai')
        {
            const currentLink = $.createLink('product', 'browse', `productID=${productID}&branch=${branch}&browseType=${browseType}&param=${param}&storyType=${storyType}&orderBy=${orderBy}&recTotal=${recTotal}&recPerPage=${recPerPage}&pageID=${pageID}&projectID=${projectID}&from=${from}&blockID=${blockID}`);
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

window.firstRendered = false;
window.toggleCheckRows = function(idList)
{
    if(!idList?.length || firstRendered) return;
    firstRendered = true;
    const dtable = zui.DTable.query($('#stories'));
    dtable.$.toggleCheckRows(idList.split(','), true);
}
