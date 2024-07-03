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

var currentStatus = instanceStatus;
const postData = new FormData();
postData.append('idList[]', instanceID);
$(function()
{
    if(inQuickon)
    {
        if(typeof timer !== 'undefined') clearInterval(timer);
        timer = setInterval(refreshStatus, 5000);
    }
})

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

                if(currentStatus != res.data[0].status)
                {
                    loadPage($.createLink('instance', 'view', `instanceID=${instanceID}`), '#setting,#statusTD,#systemLoad,.float-toolbar');
                    currentStatus = res.data[0].status;
                    return false;
                }
            }

        }
    });
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
