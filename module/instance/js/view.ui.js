$('#mainContent').on('click', '.db-management', function()
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
});

var refreshTime   = 0;
var timer         = null;
var currentStatus = instanceStatus;
const postData = new FormData();
postData.append('idList[]', instanceID);
window.afterPageUpdate = function()
{
    refreshStatus();
}

function refreshStatus()
{
    if(new Date().getTime() - refreshTime < 4000) return;
    refreshTime = new Date().getTime();

    $.ajaxSubmit({
        url: $.createLink('instance', 'ajaxStatus'),
        method: 'POST',
        data:postData,
        onComplete: function(res)
        {
            if(res.result === 'success')
            {
                $.each(res.data, function(index, instance)
                {
                    if(instance.status === 'running' && ($('#statusTD').data('reload') === true || $('#memoryRate').data('load') === true))
                    {
                        setTimeout(() => {loadCurrentPage();}, 3000);
                        currentStatus = instance.status;
                        return;
                    }
                    if(currentStatus != instance.status)
                    {
                        loadTarget(createLink('instance', 'view', `instanceID=${instanceID}`), '#instanceInfoContainer');
                        currentStatus = instance.status;
                        return;
                    }
                });
            }

            timer = setTimeout(() => {refreshStatus()}, 5000);
        }
    });
}

window.onPageUnmount = function()
{
    if(!timer) return;
    clearTimeout(timer);
}

$('.copy-btn').on('click', function()
{
    var copyText = $(this).parent().find('input');
    copyText.show();
    copyText[0].select();
    document.execCommand("Copy");
    copyText.hide();

    var that = this;
    $(that).tooltip
    ({
        trigger: 'click',
        placement: 'bottom',
        title: copied,
        tipClass: 'success',
        show:true
    });
    setTimeout(function()
    {
        $(that).tooltip('hide');
    }, 2000)
})
