function canSetDefaultBranch(obj)
{
    if(obj.value == 'active')
    {
        $(obj).closest('tr').find("input[name^='default']").removeAttr('disabled');
    }
    else
    {
        $(obj).closest('tr').find("input[name^='default']").removeAttr('checked').attr('disabled', 'disabled');
    }
}
