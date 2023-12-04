/**
 * Display the document in full screen.
 *
 * @access public
 * @return void
 */
window.fullScreen = function()
{
    var element       = document.getElementById('content');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#content').addClass('scrollbar-hover');
            $('#content').css('background', '#fff');
            $.cookie.set('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie.set('isFullScreen', 0);
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
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('#content').removeClass('scrollbar-hover');
    $.cookie.set('isFullScreen', 0);
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
    if($.cookie.get('isFullScreen') == 1) fullScreen();

    /* Update doc content silently on switch doc version, story #40503 */
    $('.panel').on('click', '#closeBtn', function()
    {
        $('#history').addClass('hidden');
        $('#hisTrigger').removeClass('text-primary');
    });

    $('#history').append('<a id="closeBtn" href="###" class="btn btn-link"><i class="icon icon-close"></i></a>');
});

window.showHistory = function()
{
    var $history = $('#history');
    var $icon    = $('#hisTrigger');
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
}
