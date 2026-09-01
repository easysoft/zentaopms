window.onRenderPromptNameCell = function(result, {row, col})
{
    if(result && col.name == 'name' && typeof timerAgentType !== 'undefined' && row.data.type == timerAgentType && timerAgentTag)
    {
        result.push({html: `<span class="label size-sm primary-pale ml-1 timer-tag">${timerAgentTag}</span>`});
    }
    return result;
};

// 切换页面显示模式
$(document)
    .off('click', '.switchButton')
    .on('click', '.switchButton', function () {
        const viewType = $(this).attr('data-type');
        $.cookie.set('aiPromptsViewType', viewType, { expires: config.cookieLife, path: config.webRoot });
        loadCurrentPage();
    });
