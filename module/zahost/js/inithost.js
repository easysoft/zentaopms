$('#checkServiceStatus').click(function(){
    $.get(createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
    {
        var resultData = JSON.parse(response);
        let html = "<h4 style='margin-top: 20px;'>" + zahostLang.initHost.statusTitle + "</h4>";
        let isSuccess = true

        for (let key in resultData.data) {
            if(resultData.data[key] == 'ready'){
                html += "<div class='text-success'><span class='dot-symbol'>●</span><span>" + key + ' ' + zahostLang.initHost[resultData.data[key]] + "</span></div>"
            }else{
                isSuccess = false
                html += "<div class='text-danger'><span class='dot-symbol'>●</span><span>" + key + ' ' + zahostLang.initHost[resultData.data[key]] + "</span></div>"
            }
        };
        
        if(isSuccess){
            html += '<h4>' + zahostLang.initHost.initSuccessNotice + '</h4>'
        }else{
            html += '<h4><div><span class="dot-symbol">' + zahostLang.initHost.initFailNotice + '</span></div><a href="https://github.com/easysoft/zenagent/" target="_blank">https://github.com/easysoft/zenagent/</a></h4>'
        }
        $('#statusContainer').html(html)
    });
    return
})