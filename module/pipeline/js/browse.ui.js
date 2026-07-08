window.renderCell = function(result, {col, row})
{
    if(col.name === 'lastStatus')
    {
        let className = '';
        if(row.data.lastStatus == 'failure' || row.data.lastStatus == 'create_fail') className = 'status-doing';
        if(row.data.lastStatus == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};
    }

    if(col.name === 'actions')
    {
        if(!result[0]) return result;
        const btnItems = result[0].props.items;

        /* 根据引擎类型决定显示编辑还是编排按钮。 */
        if(row.data.engine && row.data.engine !== 'gitfox')
        {
            result[0].props.items = btnItems.filter(function(item)
            {
                return item.icon !== 'pencil-alt';
            });
        }
        else
        {
            result[0].props.items = btnItems.filter(function(item)
            {
                return item.icon !== 'edit';
            });
        }

        if(!row.data.showVars)
        {
            result[0].props.items.forEach((item) => {
                if(item.icon == 'play')
                {
                    item.className = 'ajax-submit';
                    item['data-toggle'] = null;
                    item.url = $.createLink('pipeline', 'exec', 'execID=' + row.data.id + '&spaceID=' + row.data.spaceID + '&repoID=0&type=' + type + '&noVars=1');
                }
            })
        }
    }

    return result;
};
