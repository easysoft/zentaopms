$(function()
{
    const maxAttempts = 20; // busy 或网络错误时的最大重试次数
    const step   = 'charset';
    const tables = charsetTables || [];
    let finished = 0;

    if(!tables.length)
    {
        dataProcessScheduler.finishStep(step, true);
        return;
    }

    /**
     * 标记表处理结果。
     * Mark the process result of a table.
     */
    const markItem = function(table, status)
    {
        const $item  = $('#charsetBox .change-item[data-table="' + table + '"]');
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
     * 字符集转换完成。
     * Finish charset conversion.
     */
    const finishCharset = function()
    {
        $.getJSON($.createLink('upgrade', 'ajaxFinishCharset')).always(() =>
        {
            dataProcessScheduler.finishStep(step, true);
        });
    };

    /**
     * 逐表处理。
     * Process tables one by one.
     */
    const processOne = function(index)
    {
        if(index >= tables.length)
        {
            finishCharset();
            return;
        }

        const table = tables[index];
        const $item = $('#charsetBox .change-item[data-table="' + table + '"]');
        if($item.length) $item[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});

        const attempt = (times) =>
        {
            $.getJSON($.createLink('upgrade', 'ajaxConvertCharset', 'table=' + encodeURIComponent(table)))
                .done((response) =>
                {
                    if(response.result == 'success')
                    {
                        markItem(table, 'success');
                    }
                    else
                    {
                        markItem(table, 'fail');
                    }
                    finished++;
                    $('#charsetProcessedCount').text(finished);
                    processOne(index + 1);
                })
                .fail(() =>
                {
                    if(times < maxAttempts)
                    {
                        setTimeout(() => attempt(times + 1), 1000);
                        return;
                    }

                    markItem(table, 'fail');
                    finished++;
                    $('#charsetProcessedCount').text(finished);
                    processOne(index + 1);
                });
        };
        attempt(0);
    };

    processOne(0);
});
