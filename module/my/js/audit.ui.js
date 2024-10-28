window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'actions')
    {
        if(row.data.module == 'review')
        {
            result[0].props.items[0]['disabled']    = projectPriv ? false: true;
            result[0].props.items[0]['url']         = projectReviewLink.replace('{id}', row.data.id);
            delete result[0].props.items[0]['data-toggle'];
        }
        else if(reviewPrivs[row.data.module])
        {
            let link = reviewLink;
            if(!noFlowAuditModules.includes(row.data.module)) link = flowReviewLink;
            link = link.replace('{module}', row.data.module).replace('{id}', row.data.id);

            result[0].props.items[0]['data-toggle'] = 'modal'
            result[0].props.items[0]['disabled']    = false;
            result[0].props.items[0]['url']         = link;
            result[0].props.items[0]['href']        = link;
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
            result[0].props['data-app'] = row.data.app;
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
