window.renderAITaskCell = function(result, {row, col})
{
    if(col.name == 'name')
    {
        const name = row.data.name || '';
        if(result[0] && result[0].props) result[0].props.title = name;
    }

    if(col.name == 'status')
    {
        const status = row.data.status;
        result[0] = zui.jsx`<span class='ai-task-status status-${status}'>${aiStatusList[status]}</span>`;
    }

    if(col.name == 'type') result[0] = zui.jsx`<div title=${row.data.type}>${row.data.type}</div>`;

    return result;
}
