function batchEdit()
{
    $('#userListForm').attr('action', createLink('user', 'batchEdit', 'dept=' + deptID));
}
$(function()
{
    setTimeout(function(){fixedTfootAction('#userListForm')}, '100');
    setTimeout(function(){fixedTheadOfList('#userList')}, '100');
})
