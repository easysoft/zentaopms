window.uploadImages = function()
{
    const $uploadBox = $('[data-zui-uploadimgs]');
    const files      = document.getElementById('uploader').files;
    const length     = files.length;

    $uploadBox.children('div.py-1').html(uploadingImages.replace('%s', length));
    $('.uploadBtn').attr('disabled', 'disabled');
    if(length == 0)
    {
        $('.uploadBtn').removeAttr('disabled');
        return zui.Modal.alert(uploadEmpty);
    }

    let count = 0;
    let chunkSize = 1024 * 1024;
    for(const file of files)
    {
        const $fileItem    = $uploadBox.find('ul.file-list').find('input[value="' + file.name + '"]').closest('li.file-item');
        let   $progressBox = $fileItem.find('.file-progress');
        if($progressBox.length == 0)
        {
            $fileItem.append('<span class="alert warning circle h-5 pl-2 pr-2 file-progress absolute left-0 top-0"></span>');
            $progressBox = $fileItem.find('.file-progress');
        }

        uploadFileByChunk(uploadUrl, file, chunkSize, function(progress)
        {
            progress = Math.round(progress * 100) + '%'
            $progressBox.html(progress);
            if(progress == '100%') $progressBox.removeClass('warning').addClass('success');
        }).then(() =>
        {
            count++;
            if(count == length)
            {
                let uploadimages = $uploadBox.zui('uploadimgs');
                let modalID      = uploadimages.$element.closest('.modal').attr('id');
                zui.Modal.hide('#' . modalID);

                const $form  = $('body').find('form.form-batch[data-zui-batchform]');
                const $modal = $form.closest('.modal')
                if($modal.length > 0)
                {
                    $.ajax(
                    {
                        url: locateUrl,
                        headers:{'X-Zui-Modal': true},
                        dataType: 'json',
                        success: function(data)
                        {
                            $modal.find('[data-zui-ajaxform]').zui('ajaxform').destroy();
                            $modal.find('[data-zui-batchform]').zui('batchForm').destroy();
                            setTimeout(function()
                            {
                                loadModal(data.load, $modal.attr('id'));
                            }, 500);
                        }
                    });
                    return;
                }
                loadPage(locateUrl);
            }
        }).catch(json => {$('.uploadBtn').removeAttr('disabled'); if(typeof(json.message) != 'undefined') zui.Modal.alert(json.message)});
    }
};
