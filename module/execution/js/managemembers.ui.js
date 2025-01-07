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
    let currentAccount = $(obj).closest('tr').find('input[name^=account]').val();

    if($('#teamForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();

    if(!currentAccount) return true;

    let accountItems = JSON.parse(JSON.stringify(users));
    $('#teamForm [name^=account]').each(function()
    {
        if(!$(this).val()) return true;
        delete accountItems[$(this).val()];
    });

    const userItems = [];
    for(let key in accountItems) userItems.push({text: accountItems[key], value: key});

    $('#teamForm [name^=account]').each(function()
    {
       let $accountPicker = $(this).closest('input[name^=account]').zui('picker');
       if(typeof $accountPicker == 'undefined') return true;
       $accountPicker.render({items: userItems});
    });
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
    const dept = $('#featureBar input[name=dept]').val(); // Get dept ID.
    const link = $.createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team2Import + '&dept=' + dept); // Create manageMembers link.
    isInModal ? loadModal(link) : loadPage(link);
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
    const team = $('#featureBar input[name=execution]').val();
    const dept = $('#featureBar input[name=dept]').val();
    const link = $.createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team + '&dept=' + dept);
    isInModal ? loadModal(link) : loadPage(link);
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
