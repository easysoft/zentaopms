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
        $('#content .actions').addClass('hidden');
        requestMethod.call(element);
    }
}

document.addEventListener("fullscreenchange", function (e)
{
    if(!document.fullscreenElement) $('#content .actions').removeClass('hidden');
})

document.addEventListener("webkitfullscreenchange", function (e)
{
    if(!document.webkitFullscreenElement) $('#content .actions').removeClass('hidden');
})

document.addEventListener("mozfullscreenchange", function (e)
{
    if(!document.mozFullScreenElement) $('#content .actions').removeClass('hidden');
})

document.addEventListener("msfullscreenChange", function (e)
{
    if(!document.msfullscreenElement) $('#content .actions').removeClass('hidden');
})
