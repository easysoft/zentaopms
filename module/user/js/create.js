function changeGroup(role)
{
    if(role && roleGroup[role])
    {
        $('#group').val(roleGroup[role]); 
    }
    else
    {
        $('#group').val(''); 
    }
    $('#group').trigger("chosen:updated");
}
