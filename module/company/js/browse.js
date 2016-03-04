function batchEdit()
{
    $('#userListForm').attr('action', createLink('user', 'batchEdit', 'dept=' + deptID));
}
function manageContacts()
{
    $('#userListForm').attr('action', createLink('user', 'manageContacts'));
}
$(function()
{
    setTimeout(function(){fixedTfootAction('#userListForm')}, '100');
    setTimeout(function(){fixedTheadOfList('#userList')}, '100');
})
