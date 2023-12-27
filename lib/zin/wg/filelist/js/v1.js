$(document).ready(function()
{
    $('li.file').on('mouseover', function()
    {
        $(this).children('span.right-icon').removeClass("hidden");
        $(this).addClass('backgroundColor');
    });

    $('li.file').on('mouseout', function()
    {
        $(this).children('span.right-icon').addClass("hidden");
        $(this).removeClass('backgroundColor');
    });
});

 /**
 * Delete a file.
 *
 * @param  int    $fileID
 * @param  object $obj
 * @access public
 * @return void
 */
window.deleteFile = function(fileID, obj)
{
    if(!fileID) return;

    const method     = $(obj).closest('.files-list').parent().data('method');
    const showDelete = $(obj).closest('.files-list').parent().data('showDelete');

    if(showDelete && method == 'edit')
    {
        $('<input />').attr('type', 'hidden').attr('name', 'deleteFiles[' + fileID + ']').attr('value', fileID).appendTo('ul.files-list');
        $(obj).closest('li.file').addClass('hidden');
    }
    else
    {
        $.ajaxSubmit(
        {
            url:$.createLink('file', 'delete', 'fileID=' + fileID),
            load:true
        })
    }
}

/**
 * Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it.
 *
 * @param  int    $fileID
 * @param  string $extension
 * @param  int    $imageWidth
 * @param  string $fileTitle
 * @access public
 * @return bool
 */
window.downloadFile = function(fileID, extension, imageWidth, fileTitle)
{
    if(!fileID) return true;
    const sessionString = $('ul.files-list').parent().data('session');

    var fileTypes      = 'txt,jpg,jpeg,gif,png,bmp'.split(',');
    var windowWidth    = $(window).width();
    var width          = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    var checkExtension = fileTitle.lastIndexOf('.' + extension) == (fileTitle.length - extension.length - 1);

    var url = $.createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    url    += url.indexOf('?') >= 0 ? '&' : '?';
    url    += sessionString;

    if(fileTypes.includes(extension) && checkExtension)
    {
        zui.Modal.open({url, key: 'previewFile'});
    }
    else
    {
        open(url, '_blank');
    }
    return false;
}

/**
 * Show edit box for editing file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
window.showRenameBox = function(fileID)
{
    $('#renameFile' + fileID).closest('li').addClass('hidden');
    $('#renameBox' + fileID).closest('li').removeClass('hidden');
}

/**
 * Show File.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
window.showFile = function(fileID)
{
    $('#renameBox' + fileID).closest('li').addClass('hidden');
    $('#renameFile' + fileID).closest('li').removeClass('hidden');
}

/**
 * Smooth refresh file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
window.setFileName = function(fileID)
{
    var fileName  = $('#fileName' + fileID).val();
    var extension = $('#extension' + fileID).val();
    var postData  = {'fileName' : fileName, 'extension' : extension};
    $.ajaxSubmit(
    {
        url:$.createLink('file', 'edit', 'fileID=' + fileID),
        dataType: 'json',
        method: 'post',
        data: postData,
        load:true
    })
}
