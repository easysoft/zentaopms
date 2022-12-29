var ifm = document.getElementById("vncIframe");
ifm.height=$('.vnc-detail').css('height')
$('.vnc-mask').click(function(){
    window.open(createLink('zanode', 'getVNC', "id=" + nodeID))
})
$('.vnc-mask').css('width', $('.vnc-detail').css('width'));
$('.vnc-mask').css('height', $('.vnc-detail').css('height'));


$('#checkServiceStatus').click(function(){
    $.get(createLink('zanode', 'ajaxGetServiceStatus', 'nodeID=' + nodeID), function(response)
    {
        var resultData = JSON.parse(response);
        var isSuccess = true

        for (var key in resultData.data)
        {
            if(key == "ZTF")
            {
                $('.zenagent-status').text(zanodeLang.init[resultData.data[key]])
            }
            else
            {
                $('.ztf-status').text(zanodeLang.init[resultData.data[key]])
            }
            if(resultData.data[key] !== 'ready')
            {
                isSuccess = false
            }
        };

        if(!isSuccess)
        {
            $('.init-fail').show();
        }
        else
        {
            $('.init-success').show();
        }
    });
    return
})

$('.node-init-install').on('click', function(){
    $(this).addClass('load-indicator loading');
    var link = $(this).data('href')
    $.get(link, function(response)
    {
        $(this).removeClass('load-indicator');
        $(this).removeClass('loading');
        $('#checkServiceStatus').trigger("click")
    })
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