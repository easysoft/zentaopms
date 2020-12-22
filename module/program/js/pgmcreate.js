$(function(){$('#parent').change();})

function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#subPGMAcl').html());
    }
    else
    {
        $('.aclBox').html($('#PGMAcl').html());
    }
}

$('#future').on('change', function()
{
    if($(this).prop('checked'))
    {
        $('#budget').val('').attr('disabled', 'disabled');
    }
    else
    {
        $('#budget').removeAttr('disabled');
    }
});
