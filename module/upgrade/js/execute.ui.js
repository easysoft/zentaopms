$(function()
{
    let executedCount = 0; // 已执行的变更数量。
    let interval;          // 定时器句柄。

    /* 自动滚动版本列表。*/
    $('#versionsBox .version-item.executed').last().scrollIntoView();

    /**
     * 定时获取已执行的升级步骤数量。
     * Periodically fetch the number of executed upgrade steps.
     */
    interval = setInterval(function()
    {
        $.getJSON($.createLink('upgrade', 'ajaxGetExecutedChanges'), function(response)
        {
            const executedSqls    = response.executedSqls;
            const executedMethods = response.executedMethods;

            /**
             * 后端的一条 sql 语句可能生成多行变更记录，所以这里需要逐条对比。比如：ALTER TABLE table1 ADD COLUMN field1 ..., ADD COLUMN field2 ...
             * A single SQL statement from the backend may generate multiple change records, so we need to compare them one by one here. For example: ALTER TABLE table1 ADD COLUMN field1 ..., ADD COLUMN field2 ...
             */
            $('#changesBox .change-item').not('.executed').each(function()
            {
                const change = $(this).data('change');
                if(change.type == 'sql' && executedSqls.includes(change.sql))
                {
                    $(this).addClass('executed');
                    executedCount++;
                }
                if(change.type == 'method' && executedMethods.includes(change.method))
                {
                    $(this).addClass('executed');
                    executedCount++;
                }
            });

            $('#executedCount').text(executedCount);

            /* 自动滚动变更列表。*/
            $('#changesBox .change-item.executed').last().scrollIntoView();

            if(executedCount == upgradeChanges.length)
            {
                clearInterval(interval);
            }
        });
    }, 1000);

    /**
     * 发起请求执行升级操作。
     * Sends a request to initiate the upgrade operation.
     */
    $.getJSON($.createLink('upgrade', 'ajaxExecute', 'fromVersion=' + fromVersion + '&toVersion=' + toVersion), function(response)
    {
        clearInterval(interval);

        if(response.result == 'success')
        {
            response.load ? loadPage(response.load) : loadCurrentPage();
        }
        else if(response.result == 'fail')
        {
            const url = $.createLink('upgrade', 'execute', 'fromVersion=' + fromVersion);
            const data = new FormData();
            data.append('errors', response.message);
            postAndLoadPage(url, data);
        }
    });
});

window.showSQL = function(key)
{
    zui.Modal.alert({size: 'lg', title: 'SQL', content: {html: upgradeChanges[key].sql, className: 'leading-6'}});
}
