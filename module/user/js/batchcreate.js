function changeGroup(role, i)
{
    if(role || roleGroup[role])
    {
        $('#group' + i).val(roleGroup[role]); 
    }
    else
    {
        $('#group' + i).val(''); 
    }
}
