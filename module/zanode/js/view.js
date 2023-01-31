var checkInterval;
var intervalTimes = 0;

$('#checkServiceStatus').click(function(){
    $('#serviceContent').addClass('loading');
    checkServiceStatus();
})

function checkServiceStatus(){
    $.get(createLink('zanode', 'ajaxGetServiceStatus', 'nodeID=' + nodeID), function(response)
    {
        var resultData = JSON.parse(response);
        var isSuccess = true

        for (var key in resultData.data)
        {
            if(key == "ZTF")
            {
                if(resultData.data[key] == 'ready')
                {
                    $('.dot-ztf').removeClass("text-danger")
                    $('.dot-ztf').addClass("text-success")
                }
                else{
                    $('.dot-ztf').removeClass("text-success")
                    $('.dot-ztf').addClass("text-danger")
                }

                if(resultData.data[key] == 'ready' || resultData.data[key] == 'not_available')
                {
                    $('.ztf-status').text(zanodeLang.init[resultData.data[key]])
                    $('.ztf-install').text('');
                    $('.ztf-install-icon').hide();
                }
                else
                {
                    if(resultData.data[key] == 'unknown')
                    {
                        $('.ztf-status').text(zanodeLang.init.unknown)
                    }
                    else
                    {
                        $('.ztf-status').text(zanodeLang.initializing)
                    }
                    $('.ztf-install').text(zanodeLang.install);
                }
            }
            else if(key == "node")
            {
                if(nodeStatus != resultData.data[key] && resultData.data[key])
                {
                    window.location.reload();
                }
            }
            else
            {
                if(resultData.data[key] == 'ready')
                {
                    $('.dot-zenagent').removeClass("text-danger")
                    $('.dot-zenagent').addClass("text-success")
                }
                else{
                    $('.dot-zenagent').removeClass("text-success")
                    $('.dot-zenagent').addClass("text-danger")
                }
                $('.zenagent-status').text(zanodeLang.init[resultData.data[key]])
                if(resultData.data[key] == 'ready')
                {
                    $('.node-init-install').show();
                }
                else
                {
                    if(resultData.data[key] == 'unknown')
                    {
                        $('.ztf-zenagent').text(zanodeLang.init.unknown)
                    }
                    else
                    {
                        $('.zenagent-status').text(zanodeLang.initializing)
                    }
                }
            }
            if(resultData.data[key] !== 'ready' && key != 'node')
            {
                isSuccess = false
            }
        };

        if(!isSuccess)
        {
            // $('.init-fail').show();
            $('.init-success').hide();
        }
        else
        {
            clearInterval(checkInterval)
            $('.init-success').show();
            // $('.init-fail').hide();
        }
        setTimeout(function() {
            $('#serviceContent').removeClass('loading');
        }, 500);
    });
    return
}

$('.node-init-install').on('click', function(){
    $(this).addClass('load-indicator loading');
    var link = $(this).data('href')
    var that = this
    $.get(link, function(response)
    {
        $(that).removeClass('load-indicator');
        $(that).removeClass('loading');
        $('#checkServiceStatus').trigger("click")
    })
})

$('.btn-ssh-copy').live('click', function()
{
    var copyText = $('#ssh-copy');
    copyText.show();
    copyText .select();
    document.execCommand("Copy");
    copyText.hide();
    $('.btn-ssh-copy').tooltip({
        trigger: 'click',
        placement: 'bottom',
        title: zanodeLang.copied,
        tipClass: 'tooltip-success'
    });

    $(this).tooltip('show');
    var that = this;
    setTimeout(function()
    {
        $(that).tooltip('hide')
    }, 2000)
})

$('.btn-pwd-copy').live('click', function()
{
    var copyText = $('#pwd-copy');
    copyText.show();
    copyText .select();
    document.execCommand("Copy");
    copyText.hide();
    $('.btn-pwd-copy').tooltip({
        trigger: 'click',
        placement: 'bottom',
        title: zanodeLang.copied,
        tipClass: 'tooltip-success'
    });

    $(this).tooltip('show');
    var that = this;
    setTimeout(function()
    {
        $(that).tooltip('hide')
    }, 2000)
})

$('#jumpManual').click(function()
{
    var encodedHelpPageUrl = encodeURIComponent('https://www.zentao.net/book/zentaopms/974.html?fullScreen=zentao');
    var urlForNewTab = window.location.origin + '#app=help&url=' + encodedHelpPageUrl;
    window.open(urlForNewTab)
})


$(function(){
    checkServiceStatus();
    checkInterval = setInterval(() => {
        intervalTimes++;
        if(intervalTimes > 300)
        {
            clearInterval(checkInterval)
        }
        checkServiceStatus();
    }, 2000);
})
