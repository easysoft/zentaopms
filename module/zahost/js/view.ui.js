ajaxGetServiceStatus();
function ajaxGetServiceStatus()
{
    toggleLoading('#statusContainer', true);
    $.get($.createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
    {
        var resultData = JSON.parse(response);
        let html = "";
        let isSuccess = true
        var service = '';

        for (let key in resultData.data) {
            if(key == 'kvm')        service = 'KVM';
            if(key == 'nginx')      service = 'Nginx';
            if(key == 'novnc')      service = 'noVNC';
            if(key == 'websockify') service = 'Websockify';
            if(resultData.data[key] == 'ready'){
                html += "<div class='text-success'><span class='dot-symbol'>● </span><span>" + service + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
            }else{
                isSuccess = false
                html += "<div class='text-danger'><span class='dot-symbol'>● </span><span>" + service + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
            }
        };

        $('#statusContainer').html(html)
        if(!isSuccess){
            $('.init-fail').removeClass('hidden')
            $('.init-success').addClass('hidden')
        }
        else
        {
            $('.init-fail').addClass('hidden')
            $('.init-success').removeClass('hidden')
        }

        setTimeout(function() {
            toggleLoading('#statusContainer', false);
            $(".service-status, .status-notice").show()
        }, 500);
    });
    return;
}

function onCopy()
{
    $('#initBash').removeClass('hidden');
    document.getElementById('initBash').select();
    document.execCommand("Copy");
    $('#initBash').addClass('hidden');
    $('.btn-init-copy').tooltip({
        trigger: 'click',
        placement: 'bottom',
        title: zahostLang.copied,
        tipClass: 'tooltip-success'
    });

    $(this).tooltip('show');
    var that = this;
    setTimeout(function()
    {
        $(that).tooltip('hide')
    }, 2000)
}
