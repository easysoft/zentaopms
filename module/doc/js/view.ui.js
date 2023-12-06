/**
 * Display the document in full screen.
 *
 * @access public
 * @return void
 */
window.fullScreen = function()
{
    var element       = document.getElementById('docPanel');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullscreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#docPanel').addClass('scrollbar-hover');
            $('#docPanel').css('background', '#fff');
            $('#docPanel .panel-actions, #docPanel .selelct-version').addClass('hidden');
            $.cookie.set('isFullScreen', 1, {expires:config.cookieLife, path:config.webRoot});
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie.set('isFullScreen', 0, {expires:config.cookieLife, path:config.webRoot});
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
    $('#docPanel').removeClass('scrollbar-hover');
    $('#docPanel .panel-actions, #docPanel .selelct-version').removeClass('hidden');
    $.cookie.set('isFullScreen', 0, {expires:config.cookieLife, path:config.webRoot});
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

window.showHistory = function()
{
    const showHistory = !$('#hisTrigger').hasClass('text-primary');
    if(showHistory)
    {
        $('#history, #closeBtn').removeClass('hidden');
        $('#contentTree').addClass('hidden');
        $('#outlineToggle .icon').addClass('icon-menu-arrow-left').removeClass('icon-menu-arrow-right')
    }
    else
    {
        $('#contentTree').removeClass('hidden');
        $('#history, #closeBtn').addClass('hidden');
        $('#outlineToggle .icon').removeClass('icon-menu-arrow-left').addClass('icon-menu-arrow-right')
    }

    $('#hisTrigger').toggleClass('text-primary');
}

$(function()
{
    if($.cookie.get('isFullScreen') == 1) fullScreen();

    $('#history').append('<a id="closeBtn" href="###" class="btn btn-link hidden"><i class="icon icon-close"></i></a>');
});

$(document).on('click', '#closeBtn', function()
{
    $('#hisTrigger').removeClass('text-primary');
    $('#history, #closeBtn').addClass('hidden');
});

window.toggleOutline = function()
{
    $('#outlineToggle .icon').toggleClass('icon-menu-arrow-left').toggleClass('icon-menu-arrow-right')
    $('#contentTree').toggleClass('hidden');
}
