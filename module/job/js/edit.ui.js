window.afterPageUpdate = function()
{
    window.changeTrigger(job.triggerType == '' ? '0' : '1');
    window.changeTriggerType(job.triggerType);
    setTimeout(window.changeRepo, 10);
};
