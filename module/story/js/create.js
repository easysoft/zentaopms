$(function()
{
    $('#assignedTo').parents('tr').find('th').html($('#needNotReview').prop('checked') ? assignedTo : reviewedBy);
})

$('#needNotReview').change(function()
{
    $('#assignedTo').parents('tr').find('th').html($('#needNotReview').prop('checked') ? assignedTo : reviewedBy);
})
