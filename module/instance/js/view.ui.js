window.openAdminer = function(e)
{
    var dbName     = $(this).data('dbname');
    var dbType     = $(this).data('dbtype');
    var instanceID = $(this).data('id');

    $.ajaxSubmit
    (
        {
            url: $.createLink('instance', 'ajaxDBAuthUrl'),
            method: 'POST', data: {dbName, dbType, instanceID},
            onSuccess: (response) => 
            {
                if(response.result == 'success')
                {
                    window.open(response.data.url, 'Adminer');
                }
                else
                {
                    zui.Modal.alert(response.message);
                }
            }
        }
    );
}