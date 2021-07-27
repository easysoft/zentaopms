$(function()
{
    if($.cookie('isFullScreen') == 1) fullScreen();
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
        })
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
    var element       = document.getElementById("content");
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        $('#mainActions').removeClass('hidden');
        $('#content').addClass('scrollbar-hover');
        $('#content .actions').addClass('hidden');
        $('#content .file-image .right-icon').addClass('hidden');
        $('#content .files-list .right-icon').addClass('hidden');
        requestMethod.call(element);
        $.cookie('isFullScreen', 1);
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
    $('#content .file-image .right-icon').removeClass('hidden');
    $('#content .files-list .right-icon').removeClass('hidden');
    $.cookie('isFullScreen', 0);
}

document.addEventListener("fullscreenchange", function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
})

document.addEventListener("webkitfullscreenchange", function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
})

document.addEventListener("mozfullscreenchange", function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
})

document.addEventListener("msfullscreenChange", function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
})
