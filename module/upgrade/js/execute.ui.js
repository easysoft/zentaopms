$(function()
{
    /**
     * 定时获取已执行的升级步骤数量。
     * Periodically fetch the number of executed upgrade steps.
     */
    interval = setInterval(function()
    {
        $.getJSON($.createLink('upgrade', 'ajaxGetExecutedChanges'), function(response)
        {
            console.log(response);
            const executedSqls    = response.executedSqls;
            const executedMethods = response.executedMethods;

            let executedCount = executedMethods.length;
            /**
             * 后端的一条 sql 语句可能生成多行变更记录，所以这里需要逐条对比。比如：ALTER TABLE table1 ADD COLUMN field1 ..., ADD COLUMN field2 ...
             * A single SQL statement from the backend may generate multiple change records, so we need to compare them one by one here. For example: ALTER TABLE table1 ADD COLUMN field1 ..., ADD COLUMN field2 ...
             */
            for(const key in upgradeChanges)
            {
                const change = upgradeChanges[key];
                if(change.type == 'sql' && executedSqls.includes(change.sql))
                {
                    executedCount++;
                }
            }
            $('#executedCount').text(executedCount);
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
            loadPage(response.load);
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
