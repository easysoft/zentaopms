$(function()
{
    setTimeout(function()
    {
        $('[name=repo]').trigger('change');
        $('[name=triggerType]').trigger('change');
        window.changeTrigger(job.triggerType == '' ? '0' : '1')
    }, 10);
});
