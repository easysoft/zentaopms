$(function()
{
    let timer              = null;  // 轮询计时器
    let versionIndex       = 0;     // 当前正在处理的版本索引
    let shouldAbortAll     = false; // 标记是否应该中止所有轮询和升级操作
    let currentPollVersion = null;  // 标记当前应该处理轮询响应的版本

    function abortUpgrade()
    {
        shouldAbortAll = true;
        stopTimer();
    }

    /**
     * 停止计时器
     */
    function stopTimer()
    {
        if(timer)
        {
            clearTimeout(timer);
            timer = null;
        }
    }

    /**
     * 轮询进度：一直运行直到达到 100%
     */
    function fetchProgress(currentVersion)
    {
        return new Promise((resolve) =>
        {
            currentPollVersion = currentVersion;

            let executedCount = 0;

            const poll = () =>
            {
                if(shouldAbortAll) return;
                if(currentPollVersion !== currentVersion) return;

                $.getJSON($.createLink('upgrade', 'ajaxGetExecutedChanges'))
                    .done(function(response)
                    {
                        if(shouldAbortAll) return;
                        if(currentPollVersion !== currentVersion) return;

                        const executedKeys  = response.executedKeys;
                        const executedCount = executedKeys.length;
                        $('#changeBox .change-item').removeClass('executed');
                        executedKeys.forEach(function(key)
                        {
                            $('#changeBox .change-item[data-key="' + key + '"]').addClass('executed');
                        });
                        $('#executedCount').text(executedCount);

                        const lastExecuted = $('#changesBox .change-item.executed').last();
                        if(lastExecuted.length)
                        {
                            lastExecuted[0].scrollIntoView({behavior: 'smooth', block: 'nearest'});
                        }

                        if(response.allChangesExecuted)
                        {
                            stopTimer();
                            /* 延迟 0.5 秒便于用户看清最后的进度变化。*/
                            setTimeout(() =>
                            {
                                if(currentPollVersion === currentVersion) resolve();
                            }, 500);
                            return;
                        }

                        stopTimer();
                        if(currentPollVersion === currentVersion) timer = setTimeout(poll, 500);
                    })
                    .fail(function()
                    {
                        stopTimer();
                        if(currentPollVersion === currentVersion) timer = setTimeout(poll, 500);
                    });
            };

            poll(); // 立即开始轮询
        });
    }

    /**
     * 执行升级操作
     */
    async function runUpgrade()
    {
        const totalVersions = upgradeVersions.length;

        while(versionIndex < totalVersions)
        {
            currentPollVersion = null; // 清理上个版本的标记

            const currentVersion = upgradeVersions[versionIndex];
            const isLastVersion  = versionIndex === totalVersions - 1;

            let upgradeResult = null;

            /* 发起请求执行升级操作 */
            const upgradePromise = new Promise((resolve, reject) =>
            {
                $.getJSON($.createLink('upgrade', 'ajaxExecute', 'fromVersion=' + fromVersion + '&toVersion=' + currentVersion))
                    .done(resolve)
                    .fail(reject);
            });

            /* 更新版本列表状态为正在升级 */
            const $versionItem = $('#versionsBox .version-item[data-version="' + currentVersion + '"]');
            $versionItem.scrollIntoView({behavior: 'smooth', block: 'nearest'});
            $versionItem.find('.icon').replaceWith('<i class="icon icon-spinner-indicator text-xl text-gray-400"></i>');

            /* 启动轮询获取升级进度 */
            const pollPromise = fetchProgress(currentVersion);

            /* 等待升级请求完成 */
            try
            {
                upgradeResult = await upgradePromise;
            }
            catch(error)
            {
                /* 升级操作执行失败（网络或 HTTP 错误）*/
                abortUpgrade();
                console.error('Upgrade failed:', error);
                console.warn('An error occurred during the upgrade process. Please try again.');
                return;
            }

            /* 升级请求失败跳转到错误页面 */
            if(upgradeResult.result === 'fail')
            {
                abortUpgrade();
                const url  = $.createLink('upgrade', 'execute', 'fromVersion=' + fromVersion);
                const data = new FormData();
                data.append('errors', upgradeResult.message);
                postAndLoadPage(url, data);
                return;
            }

            /* 即使升级请求完成也要等待轮询进度达到 100% */
            await pollPromise;

            /* 更新版本列表状态为升级完成 */
            $versionItem.find('.icon').replaceWith('<i class="icon icon-check-circle text-xl text-success"></i>');

            /* 更新版本升级进度条 */
            const executedVersions = versionIndex + 1;
            const progressPercent  = Math.round(((executedVersions) / totalVersions) * 100);
            $('#versionsProgressText').text(executedVersions);
            $('#versionsProgressBar .progress-bar').css('width', progressPercent + '%');

            /* 如果是最后一个版本并且返回了跳转链接则跳转到新页面 */
            if(isLastVersion && upgradeResult.load)
            {
                abortUpgrade();
                window.location.href = upgradeResult.load;
                return;
            }

            /* 局部刷新加载下一个版本的变更列表 */
            versionIndex++;
            if(versionIndex < totalVersions)
            {
                await new Promise((resolve) =>
                {
                    loadCurrentPage(
                    {
                        selector: '#changesBlock',
                        complete: () => resolve()
                    });
                });
            }
        }

        stopTimer();
    }

    if(upgradeVersions.length > 0)
    {
        runUpgrade();
    }
});

window.showSQL = function(sql)
{
    zui.Modal.alert({size: 'lg', title: 'SQL', content: {html: sql, className: 'leading-6'}});
}
