ajaxGetServiceStatus();
function ajaxGetServiceStatus()
{
    $('#serviceContent').addClass('loading');
    $.get(createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
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
            $('.result .init-fail').removeClass('hide')
            $('.result .init-success').addClass('hide')
        }
        else
        {
            $('.result .init-fail').addClass('hide')
            $('.result .init-success').removeClass('hide')
        }

        setTimeout(function() {
            $('#serviceContent').removeClass('loading');
            $(".service-status, .status-notice").show()
        }, 500);
    });
    return

}
$('#checkServiceStatus').click(function(){
    ajaxGetServiceStatus();
})

$('.btn-init-copy').live('click', function()
{
    var copyText = $('#initBash');
    copyText.show();
    copyText.select();
    document.execCommand("Copy");
    copyText.hide();
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
})


function setIframeHeight(iframe) 
{
    if (iframe) 
    {
        var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
        if (iframeWin.document.body) 
        {
            var height = iframeWin.document.documentElement.scrollHeight || iframeWin.document.body.scrollHeight
            iframe.height = height+10;
        }
    }
}