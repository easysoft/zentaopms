function selectUsage(event)
{
    var usage = $(event.target).closest('td').find('.btn').attr('id');
    $('#mode').val(usage);
}
