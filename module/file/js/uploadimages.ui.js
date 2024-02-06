
const getChunks = (file, chunkSize) => {
    const chunks = [];
    let start = 0;
    let end = Math.min(chunkSize, file.size);

    while (start < end)
    {
        chunks.push(file.slice(start, end));
        start = end;
        end = Math.min(start + chunkSize, file.size);
    }

    return chunks;
};

const uploadChunk = (url, chunk, headers) => {
    return fetch(url, {
        method: 'POST',
        body: chunk,
        headers,
    }).then(response => response.json()).then(json => {if(json.result == 'fail') return Promise.reject(json);})
}

function uploadFileByChunk(url, file, chunkSize = 1024 * 1024, onProgress = null)
{
    const chunks = getChunks(file, chunkSize);
    let i = 0;

    return new Promise((resolve, reject) => {
        const uploadNextChunk = () => {
            if(i >= chunks.length)
            {
                if(typeof onProgress === 'function') onProgress(1);
                resolve();
                return;
            }

            const headers = {
                'X-CHUNK-INDEX': i,
                'X-TOTAL-CHUNKS': chunks.length,
                'X-FILENAME': encodeURIComponent(file.name),
                'X-FILESIZE': file.size,
            };
            uploadChunk(url, chunks[i], headers)
                .then(() => {
                    i++;
                    if(typeof onProgress === 'function') onProgress(i / chunks.length);
                    uploadNextChunk();
                })
                .catch(reject);
        };

        uploadNextChunk();
    });
};

window.uploadImages = function(event)
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
