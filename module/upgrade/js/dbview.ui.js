$(function()
{
    const step   = 'dbView';
    const $items = $('#viewBox .change-item');
    let finished = 0;

    if(!$items.length)
    {
        /* 视图定义里有待执行的语句却没有解析出视图条目时按失败处理，避免静默跳过本步骤。*/
        /* Fail the step when there are statements while no view item is parsed, so the step will not be skipped silently. */
        const expected = parseInt($('#viewBox').data('count') || 0, 10);
        dataProcessScheduler.finishStep(step, expected === 0);
        return;
    }

    /**
     * 标记视图处理结果。
     * Mark the process result of a view.
     */
    const markItem = function(view, status)
    {
        const $item  = $('#viewBox .change-item[data-view="' + view + '"]');
        const $label = $item.find('.label');
        if(status == 'success')
        {
            $label.text($label.data('doneText') || $label.data('text'));
            $label.removeClass('gray-pale text-gray-400').addClass('primary-pale text-primary');
        }
        else
        {
            $label.text($label.data('failText') || $label.data('text'));
            $label.removeClass('gray-pale text-gray-400').addClass('warning-pale text-warning');
        }
    };

    /**
     * 逐条重建数据库视图。
     * Regenerate database views one by one.
     */
    const processOne = function(index)
    {
        if(index >= $items.length)
        {
            dataProcessScheduler.finishStep(step, true);
            return;
        }

        const $item = $items.eq(index);
        const view  = $item.data('view');
        $item[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
        $.getJSON($.createLink('upgrade', 'ajaxRegenerateView', 'view=' + encodeURIComponent(view)))
            .done((response) =>
            {
                if(response.result == 'success') markItem(view, 'success');
                else markItem(view, 'fail');
                finished++;
                $('#viewProcessedCount').text(finished);
                processOne(index + 1);
            })
            .fail(() =>
            {
                markItem(view, 'fail');
                finished++;
                $('#viewProcessedCount').text(finished);
                processOne(index + 1);
            });
    };

    processOne(0);
});
