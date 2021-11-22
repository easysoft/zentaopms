/**
 * Can set default branch.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function canSetDefaultBranch(obj)
{
    if(obj.value == 'active')
    {
        if(canSetDefault) $(obj).closest('tr').find("input[name^='default']").removeAttr('disabled');
    }
    else
    {
        $(obj).closest('tr').find("input[name^='default']").removeAttr('checked').attr('disabled', 'disabled');
    }
}
