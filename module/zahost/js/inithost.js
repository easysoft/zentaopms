$('#checkServiceStatus').click(function(){
    $.get(createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
    {
        var resultData = JSON.parse(response);
        let html = "<h4 style='margin-top: 30px;'>" + zahostLang.initHost.statusTitle + "</h4>";
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
            html += '<h5>' + zahostLang.initHost.initSuccessNotice + '</h5>'
        }else{
            html += '<h5>' + zahostLang.initHost.initFailNotice + '<a href="https://github.com/easysoft/zenagent/" target="_blank">https://github.com/easysoft/zenagent/</a></h5>'
        }
        $('#statusContainer').html(html)
    });
    return
})