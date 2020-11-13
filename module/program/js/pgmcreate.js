$(function()
{
    $('#parent').change();
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
