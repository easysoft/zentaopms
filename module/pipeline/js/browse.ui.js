window.renderCell = function(result, {col, row})
{
    if(col.name === 'lastExecStatus')
    {
        let className = '';
        if(row.data.lastExecStatus == 'failure' || row.data.lastExecStatus == 'failed' || row.data.lastExecStatus == 'create_fail') className = 'status-doing';
        if(row.data.lastExecStatus == 'success') className = 'status-done';
        result[0] = {html:'<span class="' + className + '">' + result[0] + '</span>'};
    }

    if(col.name === 'actions')
    {
        if(!result[0]) return result;
        const btnItems = result[0].props.items;

        if(!row.data.showVars)
        {
            result[0].props.items.forEach((item) => {
                if(item.icon == 'play')
                {
                    item.className = 'ajax-submit';
                    item['data-toggle'] = null;
                    item.url = $.createLink('pipeline', 'exec', 'execID=' + row.data.id + '&spaceID=' + row.data.spaceID + '&repoID=' + repoID + '&type=' + type + '&noVars=1');
                    if(row.data.engine == 'jenkins')
                    {
                        if(row.data.lastExecStatus == 'pending' || row.data.lastExecStatus == 'running')
                        {
                            item['data-confirm'] = confirmExecPending;
                        }
                        else
                        {
                            item['data-confirm'] = confirmExec;
                        }
                    }
                }
            })
        }
    }

    return result;
};
