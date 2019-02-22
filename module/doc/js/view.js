$(function()
{
    if($('#urlIframe').size() > 0)
    {
        var defaultHeight = $.cookie('windowHeight') - $('#header').height() - $('#footer').height() - $('#mainMenu').height() - 50;
        $('#urlIframe').height(defaultHeight);
        setTimeout($.resetToolbarPosition, 50);
    }

    if($.cookie('fullscreen') == 'true') 
    {
        $('body').addClass('doc-fullscreen');
        $('.side-col').addClass('hidden');
        $('#mainContent .fullscreen-btn').attr('title', retrack);
        $('#mainContent .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + retrack);
    }
    else
    {
        $('body').removeClass('doc-feullscreen');
        $('.side-col').removeClass('hidden');
        $('#mainContent .fullscreen-btn').attr('title', fullscreen);
        $('#mainContent .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + fullscreen);
    }

    $('#mainContent .fullscreen-btn').click(function()
    {
        $('.side-col').toggleClass('hidden');
        $('body').toggleClass('doc-fullscreen');
        if($('body').hasClass('doc-fullscreen')) 
        {
            $('#mainContent .fullscreen-btn').attr('title', retrack);
            $('#mainContent .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + retrack);
            $.cookie('fullscreen', 'true');
        }
        else
        {
            $('#mainContent .fullscreen-btn').attr('title', fullscreen);
            $('#mainContent .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + fullscreen);
            $.cookie('fullscreen', 'false');
        }
        setTimeout($.resetToolbarPosition, 50);
    });
})

function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('file', 'delete', 'fileID=' + fileID);
}
