window.loadDBAuthUrl = function(dom)
{
    if($(dom).hasClass('disabled')) return false;

    var dbName     = $(dom).data('dbname');
    var dbType     = $(dom).data('dbtype');
    var instanceID = $(dom).data('id');

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

const postData = new FormData();
postData.append('idList[]', instanceID);
window.afterPageUpdate = function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
    if(inQuickon && instanceType == 'store') timer = setInterval(refreshStatus, 5000);
}

window.onPageUnmount = function()
{
    if(typeof timer !== 'undefined') clearInterval(timer);
}

function refreshStatus()
{
    $.ajaxSubmit({
        url: $.createLink('instance', 'ajaxStatus'),
        method: 'POST',
        data:postData,
        onComplete: function(res)
        {
            if(res.result === 'success')
            {
                if(res.data.length == 0) return false;

                loadPage($.createLink('instance', 'view', `instanceID=${instanceID}`), '#setting,#statusTD,#dbStatusTD,#systemLoad,.float-toolbar');
            }
        }
    });
}

window.copyText = function(dom)
{
    var copyText = $(dom).parent().find('input');
    copyText.show();
    copyText[0].select();
    document.execCommand("Copy");
    copyText.hide();

    zui.Messager.show({
        type:    'success',
        content: copied,
        time:    2000
    });
}
