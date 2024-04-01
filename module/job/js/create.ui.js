$(function()
{
    $('[name=engine]').trigger('change');
    $('[name=triggerType]').trigger('change');

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('#jkTask').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
});
