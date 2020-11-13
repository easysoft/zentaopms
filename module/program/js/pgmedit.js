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

$(function()
{
    $('#longTime').change(function()
    {   
        if($(this).prop('checked'))
        {   
            $('#end').attr('disabled', 'disabled');
        }   
        else
        {   
            $('#end').removeAttr('disabled');
        }   
    });
})
