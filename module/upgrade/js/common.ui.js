function updateProgressInterval() {
    interval = setInterval(function ()
    {
        updateProgress();
    }, 500);
}

let logOffset = 0;
function updateProgress()
{
    var url = $.createLink('upgrade', 'ajaxGetProgress', 'offset=' + logOffset);
    $.ajax(
    {
        url: url,
        success: function(result)
        {
            result    = JSON.parse(result);
            logOffset = result.offset;

            let progress = parseInt(result.progress);
            $("#progress .progress-bar").css('width', progress + '%');
            $('#progress .modal-title').text(progress + '%');

            if(result.log) $('#logBox').append(result.log);

            let element = document.getElementById('logBox');
            if(element.scrollHeight > 20000) element.innerHTML = element.innerHTML.substr(60000); // Remove old log.
            element.scrollTop = element.scrollHeight;

            if(progress == 100) clearInterval(interval);
        }
    });
}

window.copyCommand = function(selector)
{
    const command = $(selector).text();

    // 首先尝试使用现代的 Clipboard API (仅在 HTTPS 下可用)
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(command).then(function() {
            zui.Messager.show({type: 'success', message: copySuccess, timeout: 1000});
        }).catch(function(err) {
            console.warn('Clipboard API 失败:', err);
            fallbackCopyTextToClipboard(command);
        });
    } else {
        // HTTP 协议或不支持 Clipboard API，直接使用回退方法
        fallbackCopyTextToClipboard(command);
    }

    function fallbackCopyTextToClipboard(text) {
        const $textArea = $('<textarea>', {
            css: {
                position: 'fixed',
                top: '0',
                left: '0',
                width: '2em',
                height: '2em',
                padding: '0',
                border: 'none',
                outline: 'none',
                boxShadow: 'none',
                background: 'transparent'
            }
        });

        $('body').append($textArea);

        $textArea.val(text);
        $textArea[0].focus();
        $textArea[0].select();

        try {
            const successful = document.execCommand('copy');
            if(successful) {
                zui.Messager.show({type: 'success', message: copySuccess, timeout: 1000});
            } else {
                zui.Messager.show({type: 'danger', message: copyFail, timeout: 1000});
            }
        } catch (err) {
            zui.Messager.show({type: 'danger', message: copyFail, timeout: 1000});
        }
        $textArea.remove();
    }
}
