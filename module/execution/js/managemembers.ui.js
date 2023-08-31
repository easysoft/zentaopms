/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/%i%/g, itemIndex);
    const $currentTr = $(obj).closest('tr');

    $currentTr.after(item);
    const $newRow = $currentTr.next();
    itemIndex ++;
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
window.setDeptUsers = function()
{
    const dept = $('input[name=dept]').val(); // Get dept ID.
    const link = $.createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team2Import + '&dept=' + dept); // Create manageMembers link.
    loadPage(link);
}

/**
 * Chose team to copy.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function choseTeam2Copy()
{
    const team = $('input[name=execution]').val();
    const dept = $('input[name=dept]').val();
    const link = $.createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team + '&dept=' + dept);
    loadPage(link);
}

/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
window.setRole = function(roleID)
{
    const account = $(`input[name='account\[${roleID}\]']`).val();
    const role    = roles[account];
    const $role   = $('#role' + roleID);
    $role.val(role);
}
