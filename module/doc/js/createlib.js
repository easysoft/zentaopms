$('#project').change(function()
{
    $.get(createLink('doc', 'ajaxGetExecution', 'projectID=' + $(this).val()), function(data)
    {
        if(data)
        {
            $('#execution').replaceWith(data);
            $('#execution_chosen').remove();
            $('#execution').chosen();
        }
    });
});

function changeDoclibAcl(libType)
{
    if(libType == 'api')
    {
        $('.apilib').removeClass('hidden');
        $('#mainContent table th').css('width', '100px');
        $('#aclBox').find('td').html($('#aclAPIBox td').html());

        $('.executionBox').addClass('hidden');
    }
    else
    {
        $('.apilib').addClass('hidden');
        $('#mainContent table th').css('width', '70px');
        $('#aclBox').find('td').html($('#aclOtherBox td').html());

        $('.executionBox').removeClass('hidden');
    }
    $('#whiteListBox').addClass('hidden');
}
