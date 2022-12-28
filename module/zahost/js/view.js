ajaxGetServiceStatus();
function ajaxGetServiceStatus()
{
    $.get(createLink('zahost', 'ajaxGetServiceStatus', 'hostID=' + hostID), function(response)
    {
        var resultData = JSON.parse(response);
        let html = "";
        let isSuccess = true

        for (let key in resultData.data) {
            if(resultData.data[key] == 'ready'){
                html += "<div class='text-success'><span class='dot-symbol'>● </span><span>" + key + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
            }else{
                isSuccess = false
                html += "<div class='text-danger'><span class='dot-symbol'>● </span><span>" + key + ' ' + zahostLang.init[resultData.data[key]] + "</span></div>"
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
