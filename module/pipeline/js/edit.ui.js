$(function()
{
    setTimeout(function()
    {
        $('[name=repo]').trigger('change');
    }, 10);

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('#jkTask').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
});
