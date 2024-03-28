window.afterPageUpdate = function()
{
    window.changeTrigger(job.triggerType == '' ? '0' : '1');
    setTimeout(window.changeRepo, 10);
    window.changeTriggerType(job.triggerType);
};
