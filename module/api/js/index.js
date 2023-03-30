/**
 * Ajax delete api doc.
 *
 * @param  string $link
 * @param  int    $replaceID
 * @param  stirng $notice
 * @access public
 * @return void
 */
function ajaxDeleteApi(link, replaceID)
{
    if(confirm(confirmDelete))
    {
        $.get(link, function(data)
        {
            location.href = JSON.parse(data).locate;
        });
    }
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
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#mainActions').removeClass('hidden');
            $('#content').addClass('scrollbar-hover');
            $('#content .actions').addClass('hidden');
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
