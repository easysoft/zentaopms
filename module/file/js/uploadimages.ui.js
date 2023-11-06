window.uploadImages = function()
{
    var files  = document.getElementById('uploader').files;
    var length = files.length;

    //$('.uploadBtn').attr('disabled', 'disabled');
    if(length == 0)
    {
        $('.uploadBtn').removeAttr('disabled');
        return zui.Modal.alert(uploadEmpty);
    }

    var count = 0;
    var chunkSize = 1024 * 1024;
    for(i = 0; i < length; i++)
    {
        uploadFileByChunk(uploadUrl, files[i], chunkSize).then(() =>
        {
            count++;
            if(count == length)
            {
                var uploadimages = $('[data-zui-uploadimgs]').zui('uploadimgs');
                var modalID      = uploadimages.$element.closest('.modal').attr('id');
                zui.Modal.hide('#' . modalID);
                loadPage(locateUrl);
            }
        }).catch(json => {if(typeof(json.message) != 'undefined') zui.Modal.alert(json.message)});
    }
}
