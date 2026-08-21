/**
 * 流水线执行详情页取消执行。
 * Cancel pipeline execution in exec view page.
 */
$(function()
{
    const $cancelBtn = $('#cancelExecBtn');
    if(!$cancelBtn.length) return;

    let timer = 0;

    /**
     * 轮询执行状态，运行中/等待中时显示取消按钮。
     */
    const pollExecStatus = function()
    {
        const execID = window.executionID;
        if(!execID) return;

        $.getJSON($.createLink('pipeline', 'ajaxGetExecInfo', 'id=' + execID), function(resp)
        {
            const status = resp && resp.data ? resp.data.status : '';
            if(['running', 'pending'].includes(status))
            {
                $cancelBtn.removeClass('hidden');
            }
            else
            {
                $cancelBtn.addClass('hidden');
                if(timer)
                {
                    clearInterval(timer);
                    timer = 0;
                }
            }
        });
    };

    $cancelBtn.on('click', function()
    {
        zui.Modal.confirm({message: window.confirmCancelExec, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then(function(res)
        {
            if(!res) return;
            $.ajaxSubmit({url: $.createLink('pipeline', 'cancel', 'executionID=' + window.executionID)});
        });
    });

    pollExecStatus();
    timer = setInterval(pollExecStatus, 5000);
});
