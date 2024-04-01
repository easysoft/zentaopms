$(function()
{
    setTimeout(function()
    {
        $('[name=repo]').trigger('change');
        $('[name=triggerType]').trigger('change');
        window.changeTrigger(job.triggerType == '' ? '0' : '1')
    }, 10);

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('#jkTask').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
});
