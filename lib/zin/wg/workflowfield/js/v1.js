window.changeMode = function(event)
{
    if($(event.target).prop('checked'))
    {
        $(event.target).closest('.workflowfield').find('.fieldBox').removeClass('hidden');
        $(event.target).closest('.workflowfield').find('input[type=checkbox]').prop('checked', 'checked');
    }
    else
    {
        $(event.target).closest('.workflowfield').find('.fieldBox').addClass('hidden');
        $(event.target).closest('.workflowfield').find('input[type=checkbox]').prop('checked', '');
    }
}

window.changeAll = function(event)
{
    if($(event.target).prop('checked'))
    {
        $(event.target).closest('.fieldBox').find('input[type=checkbox]').prop('checked', 'checked');
    }
    else
    {
        $(event.target).closest('.fieldBox').find('input[type=checkbox]').prop('checked', '');
    }
}
