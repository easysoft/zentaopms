// 切换页面显示模式
$(document)
    .off('click', '.switchButton')
    .on('click', '.switchButton', function () {
        const viewType = $(this).attr('data-type');
        $.cookie.set('aiPromptsViewType', viewType, { expires: config.cookieLife, path: config.webRoot });
        loadCurrentPage();
    });
