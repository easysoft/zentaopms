$(function()
{
    $('#execution_chosen').click(function()
    {
        if(systemMode == 'new') $('#execution_chosen ul li:first').append(' <label class="label">' + projectCommon + '</label>');
    })
})

/**
 * Set role when select an account.
 *
 * @param  string $account
 * @param  int    $roleID
 * @access public
 * @return void
 */
function setRole(account, roleID)
{
    role    = roles[account];       // get role according the account.
    roleOBJ = $('#role' + roleID);  // get role object.
    roleOBJ.val(role)               // set the role.
}

function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, itemIndex);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var $accounts = $('#hours' + i).closest('tr').find('select:first')

    if($accounts.attr('data-pickertype') != 'remote')
    {
        $accounts.chosen();
    }
    else
    {
        $accounts.parent().find('.picker.picker-ready').remove();
        var pickerremote = $accounts.attr('data-pickerremote');
        $accounts.picker({chosenMode: true, remote: pickerremote});
    }
    itemIndex ++;
}

function deleteItem(obj)
{
    if($('#teamForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}

function setDeptUsers(obj)
{
    dept = $(obj).val(); // Get dept ID.
    link = createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team2Import + '&dept=' + dept); // Create manageMembers link.
    location.href=link;
}

function choseTeam2Copy(obj)
{
    team = $(obj).val();
    dept = $('#dept').val();
    link = createLink('execution', 'manageMembers', 'executionID=' + executionID + '&team2Import=' + team + '&dept=' + dept);
    location.href=link;
}
