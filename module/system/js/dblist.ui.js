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
