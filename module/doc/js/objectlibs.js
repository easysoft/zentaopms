$(function()
{
    if($.cookie('isFullScreen') == 1) fullScreen();

    $('.menu-actions > a').click(function()
    {
        $(this).parent().hasClass('open') ? $(this).css('background', 'none') : $(this).css('background', '#f1f1f1');
    })

    $('.menu-actions > a').blur(function() {$(this).css('background', 'none');})
})

/**
 * Ajax delete doc.
 *
 * @param  string $link
 * @param  int    $replaceID
 * @param  stirng $notice
 * @access public
 * @return void
 */
function ajaxDeleteDoc(link, replaceID, notice)
{
    if(confirm(notice))
    {
        $.get(link, function(data)
        {
            location.href = JSON.parse(data).locate;
        });
    }
}

/**
 * Delete a file.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
}

/**
 * Display the document in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('content');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#mainActions').removeClass('hidden');
            $('#content').addClass('scrollbar-hover');
            $('#content .actions').addClass('hidden');
            $('#content .file-image .right-icon').addClass('hidden');
            $('#content .detail').eq(1).addClass('hidden');
            $.cookie('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie('isFullScreen', 0);
        };

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }

    $('.main-col iframe').css('min-height', $(window).height() + 'px');
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('#mainActions').addClass('hidden');
    $('#content').removeClass('scrollbar-hover');
    $('#content .actions').removeClass('hidden');
    $('#content .file-image .right-icon').removeClass('hidden');
    $('#content .detail').eq(1).removeClass('hidden');
    $('.main-col iframe').css('min-height', '380px');
    $.cookie('isFullScreen', 0);
}

document.addEventListener('fullscreenchange', function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
});

document.addEventListener('webkitfullscreenchange', function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
});

document.addEventListener('mozfullscreenchange', function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
});

document.addEventListener('msfullscreenChange', function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
});

$(function()
{
    $(document).keydown(function(event)
    {
        if($.cookie('isFullScreen') == 1)
        {
            if(event.keyCode == 37) $('#prevPage').click();
            if(event.keyCode == 39) $('#nextPage').click();
        }
    });

    $('.outline').height($('.article-content').height());

    $('#content').on('click', '.outline .outline-toggle i.icon-angle-right', function()
    {
        $('.article-content').css('width', '85%');
        $('.outline').css({'min-width' : '180px', 'border-left' : '2px solid #efefef'});
        $(this).removeClass('icon-angle-right').addClass('icon-angle-left').css('left', '-9px');
        $('.outline-content').show();
        if($('#sidebar>.cell').is(':visible')) $('#sidebar .icon.icon-angle-right').trigger("click");
    }).on('click', '.outline .outline-toggle i.icon-angle-left', function()
    {
        $('.article-content').width('100%');
        $(this).removeClass('icon-angle-left').addClass('icon-angle-right');
        $('.outline-content').hide();
    }).on('click', '#outline li', function(e)
    {
        $('#outline li.active').removeClass('active');
        $(e.target).closest('li').addClass('active');
    });

    $('#outline li.has-list').addClass('open in');
    $('#outline li.has-list>i+ul').prev('i').remove();

    $(document).on('click', '.detail-content a', function(event)
    {
        var target = $(this).attr('target');
        if($.cookie('isFullScreen') == 1 && target != '_blank') exitFullScreen();
    })

    /* Update doc content silently on switch doc version, story #40503 */
    $(document).on('click', '.doc-version-menu a, #mainActions .container a', function(event)
    {
        var $tmpDiv = $('<div>');
        $tmpDiv.load($(this).data('url') + ' #mainContent', function()
        {
            $('#content').html($tmpDiv.find('#content').html());
            $('#sidebarContent').html($tmpDiv.find('#sidebarContent').html());
            $('#actionbox .histories-list').html($tmpDiv.find('#actionbox .histories-list').html());
            if($.cookie('isFullScreen') == 1) fullScreen();
            $('#content [data-ride="tree"]').tree();
            $('#outline li.has-list').addClass('open in');
            $('#outline li.has-list>i+ul').prev('i').remove();
        });
    });

    $('#sidebar .icon.icon-angle-right').click(function()
    {
        if($('#sidebar>.cell').is(':hidden') && $('.outline-content').is(':visible'))
        {
            $('.outline .outline-toggle i.icon-angle-left').trigger("click");
        }
    })

    $('.outline .outline-toggle i.icon-angle-right').trigger("click");
})
