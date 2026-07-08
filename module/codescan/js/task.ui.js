window.actionItemCreator = function (item, {row})
{
    if(item.url)
    {
        if(typeof item.url == 'string') item.url = zui.formatString(item.url, row.data);
        else item.url.params = zui.formatString(item.url.params, row.data);
    }

    if(item.icon == 'file-log')
    {
        item.url = `javascript:openPipelineLog(${row.data.repo_id}, '${row.data.pipeline_name}', ${row.data.execution_number});`;
    }
    return item;
};

window.renderTaskCell = function (result, {col, row})
{
    if(col.name == 'planName')
    {
        const planID = String(row.data.planID);

        if(!Object.keys(planList).includes(planID))
        {
            result[0] = row.data.planName;
        }
    }

    return result;
};
