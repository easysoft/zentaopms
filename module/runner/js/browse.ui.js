window.renderCell = function(result, {col, row})
{
    if(col.name === 'actions')
    {
        const actions = row.data.actions;
        actions.forEach((action) => {
            if(action.name === 'delete' && action.disabled == true) action.hint = runnerLang.notice.disableDelete;
        });

        if(typeof result[0].props.items != 'undefined' && result[0].props.items[0].icon == 'active')
        {
            delete result[0].props.items[0]['data-confirm'];
        }
    }

    if(col.name === 'runnerStatus')
    {
        const runnerStatus = row.data.runnerStatus;
        if(runnerStatus == 'offline')
        {
            result[0].props.class = result[0].props.class + ' text-danger';
        }
        else if(runnerStatus == 'online')
        {
            result[0].props.class = result[0].props.class + ' text-success';
        }
    }

    if(col.name === 'labels')
    {
        const labels = row.data.labels;
        //labels是一个,号分隔的字符串,需要把result里面的值变成一个个label标签
        const labelsArr = labels.split(',');
        console.log(labelsArr);
        let labelsHtml = '';
        labelsArr.forEach((label) => {
            if(label)
            {
                labelsHtml += '<span class="label label-default mr-1">' + label + '</span>';
            }
        });
        result[0] = {html: labelsHtml};
    }

    return result;
};
