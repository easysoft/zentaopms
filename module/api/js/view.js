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

    if($.cookie('isFullScreen') == 1) fullScreen();

    $('.menu-actions > a').click(function()
    {
        $(this).parent().hasClass('open') ? $(this).css('background', 'none') : $(this).css('background', '#f1f1f1');
    })

    $('.menu-actions > a').blur(function() {$(this).css('background', 'none');})

    /* Update doc content silently on switch doc version, story #40503 */
    $('body').on('click', '.api-version-menu a, #mainActions .container a', function(event)
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
    }).on('click', '.comment-edit-form .btn-hide-form', function()
    {
        $('.comment-edit-form #submit').attr('disabled', false);
    }).on('click', '#docVersionMenu.diff > .drop-body > li', function(e)
    {
        e.stopPropagation();
    }).on('click', '#docVersionMenu #changeBtn', function(e)
    {
        $('#docVersionMenu').addClass('diff');
        e.stopPropagation();
    }).on('click', '#hisTrigger', function()
    {
        var $history = $('#history');
        var $icon = $(this);
        if($history.hasClass('hidden'))
        {
            $history.removeClass('hidden');
            $icon.addClass('text-primary');
        }
        else
        {
            $history.addClass('hidden');
            $icon.removeClass('text-primary');
        }
    }).on('click', '#closeBtn', function()
    {
        $('#history').addClass('hidden');
        $('#hisTrigger').removeClass('text-primary');
    });

    $('#history').append('<a id="closeBtn" href="###" class="btn btn-link"><i class="icon icon-close"></i></a>');

    $('#history').find('.btn.pull-right').removeClass('pull-right');

    $('.comment-edit-form').ajaxForm({
        success: function(data) {
            location.reload();
        }
    })
});
