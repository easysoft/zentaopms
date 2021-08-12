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

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var accounts = $('#hours' + i).closest('tr').find('select:first')
    accounts.trigger('liszt:updated');
    accounts.chosen();
    i ++;
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    $(obj).closest('tr').remove();
}

/**
 * Set selected Department.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function setDeptUsers(obj)
{
    dept = $(obj).val();
    link = createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + dept + '&copyProjectID=' + copyProjectID);
    location.href = link;
}

/**
 * Chose team to copy.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function choseTeam2Copy(obj)
{
    copyProjectID = $(obj).val();
    locateLink    = createLink('project', 'manageMembers', 'projectID=' + projectID + '&dept=' + deptID + '&copyProjectID=' + copyProjectID);
    location.href = locateLink;
}
