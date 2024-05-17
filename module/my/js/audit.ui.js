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
            result[0].props.items[0]['data-toggle'] = 'modal'
            result[0].props.items[0]['disabled']    = false;
            result[0].props.items[0]['url']         = reviewLink.replace('{module}', row.data.module).replace('{id}', row.data.id);
            result[0].props.items[0]['href']        = reviewLink.replace('{module}', row.data.module).replace('{id}', row.data.id);
        }
        else
        {
            delete result[0].props.items[0];
        }
    }
    if(result && col.name == 'title')
    {
        if(row.data.module == 'review') result[0].props['data-toggle'] = '';
        if(!viewPrivs[row.data.module])
        {
            result[0].props['data-toggle'] = '';
            result[0].props['href'] = '#';
        }
    }
    return result;
}
