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
function toggleCheck(obj, i)
{
    if($(obj).val() == '')
    {
        $('#ditto' + i).attr('checked', true);
    }
    else
    {
        $('#ditto' + i).removeAttr('checked');
    }
}
