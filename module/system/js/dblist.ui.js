window.manageDb = function(dbName, dbType, namespace)
{
    $.post($.createLink('system', 'ajaxDBAuthUrl'), {dbName, namespace, dbType}, function(res)
    {
        var response = JSON.parse(res);
        if(response.result == 'success')
        {
            window.parent.open(response.data.url, 'Adminer');
        }
        else
        {
            zui.Modal.alert(response.message);
        }
    });
}

window.renderDbList = function (result, {col, row, value})
{
    if(col.name === 'status')
    {
        var statusClass = value == 'running' ? 'text-success' : '';
        result[0] = {html: '<span class="' + statusClass + '">' + result[0] + '</span>'};
        return result;
    }

    return result;
}
