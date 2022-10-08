function setDeptUsers(obj)
{
    dept = $(obj).val();//Get dept ID.
    link = createLink('stakeholder', 'batchCreate', 'projectID=' + projectID + '&dept=' + dept);
    location.href=link;
}

function addItem(obj)
{
    var item = $('#addItem').html(); 
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var $accounts = $(obj).closest('tr').next().find('select[name*=accounts]');
    $accounts.chosen();
}

function deleteItem(obj)
{
    if($('#teamForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}
