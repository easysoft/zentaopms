$('#checkServiceStatus').click(function(){
    $.get(createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
    {
        var resultData = JSON.parse(response);
        let html = "<h4 style='margin-top: 20px;'>" + zahostLang.init.statusTitle + "</h4>";
        let isSuccess = true

        for (let key in resultData.data) {
            if(resultData.data[key] == 'ready'){
                html += "<div class='text-success'><span class='dot-symbol'>●</span><span>" + key + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
            }else{
                isSuccess = false
                html += "<div class='text-danger'><span class='dot-symbol'>●</span><span>" + key + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
            }
        };

        if(!isSuccess){
            html += '<h4 style="margin-top:20px;">' + zahostLang.init.initFailNoticeTitle + '</h4>';
            html += '<div><span class="dot-symbol">' + zahostLang.init.initFailNoticeDesc + '<a href="https://github.com/easysoft/zenagent/" target="_blank">https://github.com/easysoft/zenagent/</a></span></div>'
        }
        $('#statusContainer').html(html)
        if(isSuccess) showModal();
    });
    return
})

$('.btn-init-copy').live('click', function()
{
    var copyText = $('#initBash');
    copyText.show();
    copyText .select();
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