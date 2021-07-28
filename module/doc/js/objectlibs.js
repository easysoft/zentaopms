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

$(function()
{
    $('.outline').height($('.article-content').height());

    $(document).on('click', '.outline .outline-toggle i.icon-angle-right', function()
    {
        $('.article-content').width('85%');
        $('.outline').css({'min-width' : '180px', 'border-left' : '2px solid #efefef'});
        $(this).removeClass('icon-angle-right').addClass('icon-angle-left').css('left', '-9px');
        $('.outline-content').show();
    })

    $(document).on('click', '.outline .outline-toggle i.icon-angle-left', function()
    {
        $('.article-content').width('100%');
        $(this).removeClass('icon-angle-left').addClass('icon-angle-right');
        $('.outline-content').hide();
    })

    $('.outline-content li.text-ellipsis').click(function()
    {
        console.log(this);
        $('.outline-content li.text-ellipsis.active').removeClass('active');
        $(this).addClass('active');

        event.stopPropagation();
    })

    $('#outline li.has-list').addClass('open in');
    $('#outline li.has-list>i+ul').prev('i').remove();
})
