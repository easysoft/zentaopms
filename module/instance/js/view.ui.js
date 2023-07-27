window.openAdminer = function()
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

var reloadTimes = 0;
window.afterPageUpdate = function()
{
    setTimeout(function()
    {
        if(reloadTimes > 20) return;
        if($('#statusTD').data('reload') === true || $('#memoryRate').data('load') == true)
        {
            reloadTimes++;
            fetchContent({url: $.createLink('instance', 'view', 'id=' + instanceID),
            selector: '#instanceInfoContainer',
            id: 'instanceInfoContainer',
            target: '#instanceInfoContainer',
        });
        }
    }, 4000);
}
