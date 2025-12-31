window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'actions' && typeof result[0].props != 'undefined')
    {
        if(row.data.module == 'review')
        {
            result[0].props.items[0]['disabled']    = projectPriv ? false: true;
            result[0].props.items[0]['url']         = 'javascript:checkReview("' + row.data.typeKey + '", "' + row.data.id + '")';
            delete result[0].props.items[0]['data-toggle'];
        }
        else if(reviewPrivs[row.data.module])
        {
            let link = reviewLink;
            if(['attend', 'leave', 'overtime', 'makeup', 'lieu'].includes(row.data.module))
            {
                link = oaReviewLink.replace('{module}', row.data.module);
                delete result[0].props.items[0]['data-toggle'];
            }
            else if(['mr', 'pullreq'].includes(row.data.module))
            {
                link = $.createLink(row.data.module, 'view', 'id=' + row.data.id);
                result[0].props.items[0]['disabled'] = viewPrivs[row.data.module];
                delete result[0].props.items[0]['data-toggle'];
            }
            else
            {
                if(!noFlowAuditModules.includes(row.data.module) && row.data.module != 'charter') link = flowReviewLink;
                link = link.replace('{module}', row.data.module).replace('{id}', row.data.id);

                result[0].props.items[0]['data-toggle'] = 'modal'
            }

            result[0].props.items[0]['disabled'] = false;
            result[0].props.items[0]['url']      = link;
            result[0].props.items[0]['href']     = link;
        }
        else
        {
            result[0].props.items[0]['disabled'] = true;
        }
    }
    if(result && col.name == 'title')
    {
        if(!noFlowAuditModules.includes(row.data.module))
        {
            if(row.data.app == 'scrum' || row.data.app == 'waterfall') row.data.app = 'project';
            result[0].props['data-app'] = row.data.module == 'charter' && vision == 'or' ? 'charter' : row.data.app;
        }
        if(row.data.module == 'review' || !noFlowAuditModules.includes(row.data.module)) result[0].props['data-toggle'] = '';
        if(!viewPrivs[row.data.module])
        {
            result[0].props['data-toggle'] = '';
            delete result[0].props['href'];
        }
    }
    return result;
}

window.checkReview = function(objectType, id)
{
    $.get($.createLink('review', 'ajaxCheckReviewInfo', 'objectType=' + objectType + 'reviewID=' + id), function(data)
    {
        if(!data)
        {
            zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: hasReviewedTip}).then((res) => {loadCurrentPage()});
        }
        else
        {
            openPage(projectReviewLink.replace('{id}', id), 'project');
        }
    })
}
