/**
 * Import a team.
 * 
 * @access public
 * @return void
 */
function importTeam()
{
    location.href = createLink('project', 'manageMembers', 'project=' + projectID + '&teamImport=' + $('#teams2Import').val());
}

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
