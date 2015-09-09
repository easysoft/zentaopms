$(function()
{
    if($('#needNotReview').prop('checked'))
    {
        $('#assignedTo').attr('disabled', 'disabled');
    }
    else
    {
        $('#assignedTo').removeAttr('disabled');
    }
    $('#assignedTo').trigger("chosen:updated");
    $('#assignedTo_chosen').width(150);
})

$('#needNotReview').change(function()
{
    if($('#needNotReview').prop('checked'))
    {
        $('#assignedTo').attr('disabled', 'disabled');
    }
    else
    {
        $('#assignedTo').removeAttr('disabled');
    }
    $('#assignedTo').trigger("chosen:updated");
})
