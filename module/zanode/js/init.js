$('#checkServiceStatus').click(function(){
    $.get(createLink('zanode', 'ajaxGetServiceStatus', 'nodeID=' + nodeID), function(response)
    {
        var resultData = JSON.parse(response);
        var html = "<h4 style='margin-top: 20px;'>" + zanodeLang.init.statusTitle + "</h4>";
        var isSuccess = true

        for (var key in resultData.data)
        {
            var installHtml = "";
            if(key == "ZTF")
            {
                installHtml = "<a class='node-init-install' target='hiddenwin' herf='javascript:;' data-href='"+createLink('zanode', 'ajaxInstallService', 'nodeID=' + nodeID + '&service=' + key)+"'>"+zanodeLang.install+"</a>";
            }
            if(resultData.data[key] == 'ready')
            {
                html += "<div class='text-success'><span class='dot-symbol'>●</span><span>" + key + ' ' + zanodeLang.init[resultData.data[key]] + "</span>"+installHtml+"</div>"
            }
            else
            {
                isSuccess = false
                html += "<div class='text-danger'><span class='dot-symbol'>●</span><span>" + key + ' ' + zanodeLang.init[resultData.data[key]] + "</span>"+installHtml+"</div>"
            }
        };

        if(!isSuccess)
        {
            html += '<h4 style="margin-top:20px;">' + zanodeLang.init.initFailNoticeTitle + '</h4>';
            html += '<div><span class="dot-symbol">' + zanodeLang.init.initFailNoticeDesc + '<a href="https://github.com/easysoft/zenagent/" target="_blank">https://github.com/easysoft/zenagent/</a></span></div>'
        }
        $('#statusContainer').html(html)
        if(isSuccess) showModal();
    });
    return
})

$('#statusContainer').on('click', '.node-init-install', function(){
    $(this).addClass('load-indicator loading');
    var link = $(this).data('href')
    $.get(link, function(response)
    {
        $(this).removeClass('load-indicator');
        $(this).removeClass('loading');
        $('#checkServiceStatus').trigger("click")
    })
})
