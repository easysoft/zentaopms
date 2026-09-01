/**
 * 调用 SSE 接口并输出返回值
 * Call SSE endpoint and output returned values
 */
function connectSSE() {
    // 如果已存在相同的 SSE 连接，则先关闭它
    if (window.eventSource && window.eventSource.readyState !== EventSource.CLOSED) {
        window.eventSource.close();
        console.log('Existing SSE connection closed');
    }

    // 用于存储 EventSource 实例
    window.eventSource = new EventSource(sseURL);

    // 重连计数器
    let reconnectAttempts = 0;
    const maxReconnectAttempts = 5;

    // 重连间隔时间（毫秒）
    const reconnectInterval = 5000;

    window.eventSource.onopen = function(event) {
        console.log('SSE Connected:', event);
        // 重置重连计数器
        reconnectAttempts = 0;
    };

    // 监听默认消息
    window.eventSource.onmessage = function(event) {
        console.log('Message:', event);
    };

    window.eventSource.addEventListener('pullreq_updated', function(event)
    {
        const eventData = JSON.parse(event.data);
        console.log('pullreq_updated', eventData);
        if(mrID == eventData.id)
        {
            $.getJSON($.createLink('ppm', 'ajaxCheckReviewFlow', 'id=' + mrID));
            loadCurrentPage('#mr-detail');
        }
    });

    window.eventSource.onerror = function(error) {
        console.error('SSE Error:', error);
        if (window.eventSource.readyState === EventSource.CLOSED) {
            console.log('closed', 'Connected closed');

            // 尝试重连
            if (reconnectAttempts < maxReconnectAttempts) {
                reconnectAttempts++;
                console.log(`Attempting to reconnect... (${reconnectAttempts}/${maxReconnectAttempts})`);

                // 使用 setTimeout 实现延迟重连
                setTimeout(() => {
                    connectSSE();
                }, reconnectInterval);
            } else {
                console.log('Max reconnection attempts reached. Giving up.');
            }
        } else {
            console.log('error', 'Connected error, reconnecting...');
        }
    };
}

/**
 * 在当前页面用modal加载链接。
 * Load link object page.
 *
 * @param  string $link
 * @access public
 * @return void
 */
window.loadLinkPage = function(link)
{
    $('#linkObject').attr('href', link);
    $('#linkObject').trigger('click');
}

window.loadMergeBtn = function(mergeType)
{
    $.cookie.set('mergeType', mergeType, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage('#mr-detail');
}

/**
 * Toggle fullscreen for files tab and recalculate content heights.
 *
 * @access public
 * @return void
 */
window.toggleFilesFullscreen = function()
{
    var $target   = $('#files-tab');
    var wasInFull = $target.hasClass('is-in-fullscreen');

    $target.fullscreen();

    /* Reset height caches so getIframeHeight/getSidebarHeight recalculate. */
    iframeHeight  = 0;
    sidebarHeight = 0;

    setTimeout(function()
    {
        if(!wasInFull)
        {
            /* Entering fullscreen: cache fullscreen-height values. */
            iframeHeight  = $(window).height() - 110;
            sidebarHeight = $(window).height() - 100;
        }

        updateFilesContentHeight();
    }, 300);
};

/**
 * Update iframe and sidebar heights to match current viewport.
 *
 * @access public
 * @return void
 */
window.updateFilesContentHeight = function()
{
    var height     = getIframeHeight();
    var treeHeight = getSidebarHeight();
    var isFS       = $('#files-tab').hasClass('is-in-fullscreen');

    $('#files-tab iframe.repo-iframe').each(function()
    {
        $(this).attr('height', height);
    });

    $('#monacoTree').css('height', (treeHeight - (isFS ? 88 : 8)) + 'px');
};

/* Watch for ZUI fullscreen exit (close button / ESC) and recalculate heights. */
$(function()
{
    var $target = $('#files-tab');
    if(!$target.length) return;

    var observer = new MutationObserver(function(mutations)
    {
        mutations.forEach(function(mutation)
        {
            if(mutation.type === 'attributes' && mutation.attributeName === 'class')
            {
                if(!$target.hasClass('is-in-fullscreen'))
                {
                    /* Fullscreen exited via ZUI close button or ESC. */
                    iframeHeight  = 0;
                    sidebarHeight = 0;
                    setTimeout(function()
                    {
                        updateFilesContentHeight();
                    }, 300);
                }
            }
        });
    });

    observer.observe($target[0], {attributes: true, attributeFilter: ['class']});
});
