window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'actions')
    {
        if(reviewPrivs[row.data.module])
        {
            result[0].props.items[0]['data-toggle'] = 'modal'
            result[0].props.items[0]['disabled']    = false;
            result[0].props.items[0]['url']         = reviewLink.replace('{module}', row.data.module).replace('{id}', row.data.id);;
            result[0].props.items[0]['href']        = reviewLink.replace('{module}', row.data.module).replace('{id}', row.data.id);;
        }
        else
        {
            result[0].props.items[0]['disabled'] = true;
            delete result[0].props.items[0]['data-toggle'];
            delete result[0].props.items[0]['url'];
            delete result[0].props.items[0]['href'];
        }
    }
    return result;
}

