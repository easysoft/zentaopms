
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
    }).then(response => response.json()).then(json => {
        if(json.result == 'fail') return Promise.reject(json);
        return new Promise(resolve => setTimeout(() => resolve(json), 100)); // 增加延迟，给后端处理session的时间
    });
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

/**
 * 控制并发上传数量
 * @param {File[]} files
 * @param {string} uploadUrl
 * @param {number} chunkSize
 * @param {number} maxConcurrency
 */
const uploadFilesWithConcurrency = async (files, uploadUrl, chunkSize, maxConcurrency = 2) => {
    const results = [];
    const inProgress = new Set();

    const uploadFile = async (file, fileIndex) => {
        return new Promise((resolve, reject) => {
            uploadFileByChunk(uploadUrl, file, chunkSize, function(progress) {
            }).then(() => {
                resolve({ file, fileIndex, success: true });
            }).catch(error => {
                reject({ file, fileIndex, error });
            });
        });
    };

    const processQueue = async () => {
        for (let i = 0; i < files.length; i++) {
            if (inProgress.size >= maxConcurrency) {
                await Promise.race(inProgress);
            }

            const file = files[i];
            const uploadPromise = uploadFile(file, i).finally(() => {
                inProgress.delete(uploadPromise);
            });

            inProgress.add(uploadPromise);
            results.push(uploadPromise);
        }

        await Promise.all(results);
    };

    await processQueue();
    return results;
};

window.uploadImages = function(selector, options, $uploadBtn)
{
    const $fileBox = $(selector);
    const fileBox  = $fileBox.zui();
    const files    = fileBox.$.files;

    if(!files.length)
    {
        if(!$uploadBtn.hasClass('disabled')) zui.Modal.alert(options.errorUploadEmpty);
        return;
    }

    const progressMap = new Map();
    let uploadedCount = 0;
    $uploadBtn.attr('disabled', 'disabled');
    $uploadBtn.find('.as-progress').text(' 0%');
    const render = () =>
    {
        fileBox.render({disabled: true, itemProps: (file) =>
        {
            const progress = progressMap.get(file.file);
            if(progress === undefined) return {};
            if(progress === 1) return {icon: 'check text-success absolute right-0 bottom-2'};

            return {title: Math.round(progress * 100) + '% | ' + file.name};
        }});
    };

    render();

    /* 串行上传文件 */
    const uploadFilesSequentially = async (fileIndex = 0) => {
        if (fileIndex >= files.length) {
            const modalID = $uploadBtn.closest('.modal').attr('id');
            zui.Modal.hide('#' + modalID);

            const $form  = $('body').find('form.form-batch[data-zui-batchform]');
            const $modal = $form.closest('.modal')
            if($modal.length > 0)
            {
                $.ajax(
                {
                    url: options.locateUrl,
                    headers:{'X-Zui-Modal': true},
                    dataType: 'json',
                    success: function(data)
                    {
                    }
                });
                return;
            }
            loadPage(options.locateUrl);
            return;
        }

        const file = files[fileIndex];
        try {
            await uploadFileByChunk(options.uploadUrl, file, options.chunkSize, function(progress)
            {
                progressMap.set(file, progress);
                render();
                $uploadBtn.find('.as-progress').text(' ' + Math.round((uploadedCount + progress) / files.length * 100) + '%' );
            });

            uploadedCount++;
            uploadFilesSequentially(fileIndex + 1);
        } catch (error) {
            $uploadBtn.removeAttr('disabled');
            $uploadBtn.find('.as-progress').text('');
            fileBox.render({disabled: false});
            if(typeof(error.message) != 'undefined') zui.Modal.alert(error.message);
        }
    };

    uploadFilesSequentially();
};
