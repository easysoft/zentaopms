window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'actions')
    {
        if(row.data.module == 'review')
        {
            result[0].props.items[0]['disabled']    = projectPriv ? false: true;
            result[0].props.items[0]['url']         = projectReviewLink.replace('{id}', row.data.id);
        }
        else if(reviewPrivs[row.data.module])
        {
            link = reviewLink;
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
            delete result[0].props.items[0]['data-toggle'];
            delete result[0].props.items[0]['url'];
            delete result[0].props.items[0]['href'];
        }
    }
    if(result && col.name == 'title' && row.data.module == 'review')
    {
        result[0].props['data-toggle'] = '';
    }
    return result;
}
