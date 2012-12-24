function batchEdit()
{
    $('#userListForm').attr('action', createLink('user', 'batchEdit', 'dept=' + deptID));
}
function manageContacts()
{
    $('#userListForm').attr('action', createLink('user', 'manageContacts'));
}
