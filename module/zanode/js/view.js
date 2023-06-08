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
                        if(hostType == 'physics')
                        {
                            $('.ztf-status').text(zanodeLang.init.not_install)
                        }
                        else
                        {
                            $('.ztf-status').text(zanodeLang.init.unknown)
                        }
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
                if(resultData.data[key] && zanodeLang.statusList[nodeStatus] != zanodeLang.statusList[resultData.data[key]])
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
            $('.init-fail').show();
            $('.init-success').hide();
        }
        else
        {
            clearInterval(checkInterval)
            $('.init-success').show();
            $('.init-fail').hide();
        }
        setTimeout(function() {
            $('#serviceContent').removeClass('loading');
            $(".service-status, .status-notice").show()
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

$('.btn-init-copy').live('click', function()
{
    var copyText = $('#initBash');
    copyText.show();
    copyText .select();
    document.execCommand("Copy");
    copyText.hide();
    $('.btn-init-copy').tooltip({
        trigger: 'click',
        placement: 'top',
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

$('.btn-pwd-show').live('click', function()
{
    var pwd     = $('#pwd-copy').text();
    var pwdStar = "******".padEnd(pwd.length, '*')
    var html    = $(this).html()
    if(html.indexOf('icon-eye-off') != -1)
    {
        $('#pwd-text').text(pwdStar)
        $(this).html("<i class='icon-common-eye icon-eye' title='" + zanodeLang.showPwd +  "'></i>")
    }
    else
    {
        $('#pwd-text').text(pwd)
        $(this).html("<i class='icon-common-eye icon-eye-off' title='" + zanodeLang.hidePwd +  "'></i>")
    }
})

$('#jumpManual').click(function()
{
    var encodedHelpPageUrl = encodeURIComponent('https://www.zentao.net/book/zentaopms/974.html?fullScreen=zentao');
    var urlForNewTab = webRoot + '#app=help&url=' + encodedHelpPageUrl;
    window.open(urlForNewTab)
})


$(function(){
    $('#checkServiceStatus').trigger("click")
    if(hostType == 'physics'){
        return;
    }
    checkInterval = setInterval(() => {
        intervalTimes++;
        if(intervalTimes > 200)
        {
            clearInterval(checkInterval)
        }
        checkServiceStatus();
    }, 3000);
})

/**
 * Edit Snapshot.
 *
 * @param string $link
 * @access public
 * @return void
 */
function editSnapshot(link)
{
    $('#editSnapshot').attr('href', link).click();
}
