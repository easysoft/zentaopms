$(function()
{
    setTimeout(function()
    {
        $('[name=engine]').trigger('change');
    }, 100)

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('#jkTask').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
});
