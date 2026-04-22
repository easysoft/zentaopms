$(function()
{
    function setStatus(type, text)
    {
        const $statusBox = $('#statusBox');
        $statusBox.removeClass('hidden success-pale text-success danger-pale text-danger');
        if(type === 'success') $statusBox.addClass('success-pale text-success');
        if(type === 'danger') $statusBox.addClass('danger-pale text-danger');
        $statusBox.html(String(text).replace(/\n/g, '<br />'));
        $('#changesBox').height($('#changesBox').height() - $statusBox.outerHeight());
    }

    function updateProgress(executedCount)
    {
        let executed = 0;
        $('#changesBox .change-item').not('.executed').each(function()
        {
            if(executed >= executedCount) return false;

            const $item  = $(this);
            const $label = $item.find('.label');
            $item.addClass('executed');
            $label.text($label.data('text'));
            $label.removeClass('gray-pale text-gray-400').addClass('primary-pale text-primary');

            executed++;
        });

        const $executedItems = $('#changesBox .change-item.executed');
        const $lastExecuted  = $executedItems.last();
        if($lastExecuted.length)
        {
            const $nextItem  = $lastExecuted.next('.change-item');
            const scrollItem = $nextItem.length ? $nextItem[0] : $lastExecuted[0];
            scrollItem.scrollIntoView({behavior: 'smooth', block: 'nearest'});
        }

        const totalCount      = $('#changesBox .change-item').length;
        const totalExecuted   = $executedItems.length;
        const progressPercent = totalCount ? Math.round((totalExecuted / totalCount) * 100) : 100;
        $('#changesProgressText').text(totalExecuted + ' / ' + totalCount);
        $('#changesProgressBar .progress-bar').css('width', progressPercent + '%');
    }

    function runInstall()
    {
        $.getJSON($.createLink('install', 'ajaxCreateTable'))
            .done(function(response)
            {
                const executedCount = response.executedCount || 0;
                updateProgress(executedCount);

                if(response.result === 'fail' || response.error)
                {
                    setStatus('danger', response.message || 'Install failed.');
                    return;
                }

                if(response.allChangesExecuted)
                {
                    setStatus('success', dbFinish);
                    $('#nextBtn').removeClass('disabled').attr('href', $.createLink('install', 'step3'));
                    return;
                }

                setTimeout(runInstall, 200);
            })
            .fail(function()
            {
                setStatus('danger', dbFail);
            });
    }

    runInstall();
});

window.showSQL = function(sql)
{
    zui.Modal.alert({size: 'lg', title: 'SQL', content: {html: sql, className: 'leading-6'}});
}
