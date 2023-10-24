$(function()
{
    $('button.db-login').on('click', function(event)
    {
        var dbName    = $(event.target).data('db-name');
        var dbType    = $(event.target).data('db-type');
        var namespace = $(event.target).data('namespace');

        $.post(createLink('system', 'ajaxDBAuthUrl'), {dbName, namespace, dbType}).done(function(res)
        {
            var response = JSON.parse(res);
            if(response.result == 'success')
            {
                window.parent.open(response.data.url, 'Adminer');
            }
            else
            {
                bootbox.alert(response.message);
            }
        });
    });
});
