function setDeptUsers(obj)
{
    dept = $(obj).val();//Get dept ID.
    link = createLink('program', 'createstakeholder', 'programID=' + programID + '&dept=' + dept);
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
    $(obj).closest('tr').remove();
}
