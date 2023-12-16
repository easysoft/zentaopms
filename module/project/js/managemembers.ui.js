/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
window.setRole = function(account, roleID)
{
    const role  = roles[account];
    const $role = $('#role' + roleID);
    $role.val(role);
}

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/_i/g, itemIndex);
    const $currentTr = $(obj).closest('tr');
    $currentTr.after(item);

    const $newRow = $currentTr.next();
    $('select[name^=account]').each(function()
    {
        const selectValue = $(this).val();
        if(selectValue) $newRow.find(`option[value='${selectValue}']`).remove();
    });
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.deleteItem = function(obj)
{
    if($('#teamForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}

/**
 * Set dept users.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.setDeptUsers = function(e)
{
    const dept = $(e.target).val(); // Get dept ID.
    const link = $.createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID); // Create manageMembers link.
    loadPage(link);
}

/**
 * Chose team to copy.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function choseTeam2Copy(e)
{
    const copyProjectID = $(e.target).val();
    const dept          = $('input[name=dept]').val();
    const link          = $.createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID);
    loadPage(link);
}

window.changeProjectMembers = function()
{
    let isDeleted   = false;
    let accountList = [];
    $("[name^='account']").each(function()
    {
        if($(this).val()) accountList.push($(this).val());
    });

    oldAccountList.forEach(function(account)
    {
        if(accountList.indexOf(account) < 0)
        {
            isDeleted = true;
            return false;
        }
    });

    if(!isDeleted)
    {
        const formData = new FormData($("#teamForm")[0]);
        $.ajaxSubmit({url: $('#teamForm').attr('action'), data: formData});
    }
    else
    {
        zui.Modal.confirm({message: unlinkExecutionMembers}).then((res) =>
        {
            if(res)
            {
                $('#removeExecution').val('yes');
                const formData = new FormData($("#teamForm")[0]);
                $.ajaxSubmit({url: $('#teamForm').attr('action'), data: formData});
            }
        });
    }
    return false;
}
