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

    resetAccountItems();
    if(!currentAccount) return true;

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

    resetAccountItems();
}

function resetAccountItems()
{
    let selectedAccounts = [];
    $('#teamForm [name^=account]').each(function()
    {
        if(!$(this).val()) return true;
        selectedAccounts.push($(this).val());
    });

    $('#teamForm [name^=account]').each(function()
    {
        let $accountPicker = $(this).closest('input[name^=account]').zui('picker');
        if(typeof $accountPicker == 'undefined') return true;

        let userItems      = $accountPicker.options.items;
        let currentAccount = $(this).val();
        for(let key in userItems)
        {
            let disabled = selectedAccounts.includes(userItems[key].value) && userItems[key].value != currentAccount ? true : false;
            userItems[key].disabled = disabled;
        }
        $accountPicker.render({items: userItems});
    });
}
