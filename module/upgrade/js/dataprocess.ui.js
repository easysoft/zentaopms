$(function()
{
    const maxAttempts = 20; // busy 或网络错误时的最大重试次数
    const steps       = dataProcessSteps || [];
    const totalSteps  = steps.length;
    const preFinished = dataProcessFinishedSteps || [];
    let finishedStepCount = 0;
    let currentStep  = '';
    let stepResolved = null;

    /**
     * 更新左侧步骤状态。
     * Update the status of a step in the left panel.
     */
    const markStep = function(step, status)
    {
        const $item  = $('#stepsBox .step-item[data-step="' + step + '"]');
        if(!$item.length) return;

        const $icon = $item.find('.step-icon');
        if(status == 'doing')
        {
            $icon.replaceWith('<span class="step-icon"><i class="icon icon-spinner-indicator animate-spin text-gray-400 w-4 h-4"></i></span>');
        }
        else if(status == 'done')
        {
            $icon.replaceWith('<span class="step-icon"><span class="bg-success rounded-full w-4 h-4 inline-block"></span></span>');
        }
        else
        {
            $icon.replaceWith('<span class="step-icon"><i class="icon icon-exclamation-sign text-warning w-4 h-4"></i></span>');
        }
    };

    /**
     * 更新左侧总进度。
     * Update the total progress in the left panel.
     */
    const updateTotalProgress = function()
    {
        const percent = totalSteps > 0 ? Math.round((finishedStepCount / totalSteps) * 100) : 100;
        $('#stepsProgressText').text(finishedStepCount);
        $('#stepsProgressBar .progress-bar').css('width', percent + '%');
    };

    /**
     * 记录单个数据处理步骤的完成标记。
     * Write the finish marker of one data process step.
     */
    const writeFinishMarker = function(step)
    {
        return new Promise((resolve, reject) =>
        {
            const attempt = (times) =>
            {
                $.getJSON($.createLink('upgrade', 'ajaxFinishDataProcessStep', 'step=' + step))
                    .done((response) =>
                    {
                        if(response.result == 'success')
                        {
                            resolve(true);
                            return;
                        }
                        if(times < maxAttempts)
                        {
                            setTimeout(() => attempt(times + 1), 1000);
                            return;
                        }
                        reject();
                    })
                    .fail(() =>
                    {
                        if(times < maxAttempts)
                        {
                            setTimeout(() => attempt(times + 1), 1000);
                            return;
                        }
                        reject();
                    });
            };
            attempt(0);
        });
    };

    /**
     * 通过 ajax 加载步骤页到右侧区域，等待步骤页处理完成后回调。
     * Load a step page into the right panel via ajax, then wait for its finish callback.
     */
    const loadStepPage = function(step)
    {
        return new Promise((resolve) =>
        {
            const attempt = (times) =>
            {
                stepResolved = resolve;
                $('#progressBlock').load($.createLink('upgrade', step), function(response, status)
                {
                    if(status == 'error' && times < maxAttempts)
                    {
                        stepResolved = null;
                        setTimeout(() => attempt(times + 1), 1000);
                        return;
                    }

                    if(status == 'error')
                    {
                        stepResolved = null;
                        resolve(false);
                    }
                    /* 加载成功时保持 stepResolved，等待步骤页调用 finishStep。*/
                });
            };
            attempt(0);
        });
    };

    /**
     * 提供给步骤页的调度器接口。
     * The scheduler API exposed to step pages.
     */
    const showSQL = function(sql)
    {
        zui.Modal.alert({size: 'lg', title: 'SQL', content: {html: sql, className: 'leading-6'}});
    };

    /* 步骤页视图中的内联 SQL 链接调用全局 showSQL。*/
    window.showSQL = showSQL;

    window.dataProcessScheduler = {
        finishStep: function(step, success)
        {
            if(currentStep != step || !stepResolved) return;

            const resolve = stepResolved;
            stepResolved = null;
            resolve(success === false ? false : true);
        },
        showSQL: showSQL
    };

    /**
     * 依次执行数据处理步骤。
     * Run data process steps in order.
     */
    const run = async function()
    {
        if(!totalSteps)
        {
            $('#continueBtn').removeClass('disabled');
            return;
        }

        for(const step of steps)
        {
            currentStep = step;
            if(preFinished.includes(step))
            {
                markStep(step, 'done');
                finishedStepCount++;
                updateTotalProgress();
                continue;
            }

            markStep(step, 'doing');
            const success = await loadStepPage(step);

            /* 步骤页加载失败时不写完成标记，刷新后自动重试；其余情况按步骤结果写入标记。*/
            if(success)
            {
                try
                {
                    await writeFinishMarker(step);
                }
                catch(error)
                {
                    zui.Modal.alert(executeFailed);
                    return;
                }
            }

            markStep(step, success ? 'done' : 'fail');
            finishedStepCount++;
            updateTotalProgress();
        }

        $('#continueBtn').removeClass('disabled');
    };

    updateTotalProgress();
    run();
});
