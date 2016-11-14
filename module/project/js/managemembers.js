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

function addItem()
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $('#submit').before('<tr class="addedItem">' + item  + '</tr>');
    var accounts = $('#submit').closest('table').find('tr.addedItem:last').find('select:first')
    accounts.trigger('liszt:updated');
    accounts.chosen(defaultChosenOptions);
    i ++;
}

function deleteItem(obj)
{
    $(obj).closest('.addedItem').remove();
}

function setDeptUsers(obj)
{
    dept = $(obj).val();//Get dept ID.
    link = createLink('project', 'manageMembers', 'projectID=' + projectID + '&team2Import=' + team2Import + '&dept=' + dept);//Create manageMembers link.
    location.href=link;
}

function choseTeam2Copy(obj)
{
    team = $(obj).val();
    link = createLink('project', 'manageMembers', 'projectID=' + projectID + '&team2Import=' + team);
    location.href=link;
}
