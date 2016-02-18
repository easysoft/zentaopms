function setForm(){}
$(document).ready(function()
{
    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    var mBtn = $('#mobile');
    mBtn.popover({html: true, container: 'body'}).click(function(event)
    {
        event.stopPropagation();
        $(document).one('click', function()
        {
            $('#mobile').popover('hide');
        });
    });
    mBtn.attr('title', mBtn.attr('data-original-title'));
    showNotice();
})

/**
 * Show browser notice 
 * 
 * @access public
 * @return void
 */
function showNotice()
{
    var show = false;
    var type = '';
    /* IE 6,7. */
    if($.browser.msie && $.browser.version <= 7)
    {
        show = true;
        type = 'incompatible';
    }

    /* Souhu */
    if(window.clientInformation.userAgent.indexOf('MetaSr') >= 0)
    {
        show = true;
        type = 'notice';
    }
    else if(window.clientInformation.userAgent.indexOf('LBBROWSER') >= 0)
    {
        show = true;
        type = 'notice';
    }
    else if(window.clientInformation.userAgent.indexOf('QQBrowser') >= 0)
    {
        show = true;
        type = 'notice';
    }
    else if(window.clientInformation.userAgent.indexOf('TheWorld') >= 0)
    {
        show = true;
        type = 'notice';
    }
    else if(window.clientInformation.userAgent.indexOf('BIDUBrowser') >= 0)
    {
        show = true;
        type = 'notice';
    }
    else if(window.clientInformation.userAgent.indexOf('Maxthon') >= 0)
    {
        show = true;
        type = 'notice';
    }
    /* 360. */
    else if($.browser.chrome && !(window.clientInformation && window.clientInformation.mediaDevices))
    {
        show = true;
        type = 'notice';
    }
    if(show) $('body').prepend('<div class="alert ' + (type == 'notice' ? 'alert-info' : 'alert-danger') + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p>' + browser[type] + '</p></div>');
}
