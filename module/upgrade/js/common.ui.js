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
