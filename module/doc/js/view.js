$(function()
{
    if($('#urlIframe').size() > 0)
    {
        var defaultHeight = $.cookie('windowHeight') - $('#header').height() - $('#footer').height() - $('#mainMenu').height() - 50;
        $('#urlIframe').height(defaultHeight);
        setTimeout($.resetToolbarPosition, 50);
    }

    var isFullscreen = $.cookie('docFullscreen') == 'true';
    $('body').toggleClass('doc-fullscreen', isFullscreen);
    $('.side-col').toggleClass('hidden', isFullscreen);
    $('#mainMenu .fullscreen-btn').attr('title', isFullscreen ? retrack : fullscreen);
    $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + (isFullscreen ? retrack : fullscreen));

    $('#mainMenu .fullscreen-btn').click(function()
    {
        $('.side-col').toggleClass('hidden');
        $('body').toggleClass('doc-fullscreen');

        var isFullscreen = $('body').hasClass('doc-fullscreen');
        $('#mainMenu .fullscreen-btn').attr('title', isFullscreen ? retrack : fullscreen);
        $('#mainMenu .fullscreen-btn').html('<i class="icon icon-fullscreen"></i> ' + (isFullscreen ? retrack : fullscreen));
        $.cookie('docFullscreen', isFullscreen);

        setTimeout($.resetToolbarPosition, 50);
    });

    if(!canDeleteFile)
    {
        $('.detail-content .files-list li').each(function()
        {
            $(this).find('a[onclick^=deleteFile]').remove();
        });
    }
})

function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('doc', 'deleteFile', 'docID=' + docID + '&fileID=' + fileID);
}
