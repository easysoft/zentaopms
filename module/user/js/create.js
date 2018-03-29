function changeGroup(role)
{
    if(role && roleGroup[role])
    {
        $('#group').val(roleGroup[role]); 
    }
    else
    {
        $('#group').val(''); 
    }
    $('#group').trigger("chosen:updated");
}
/*
   设置用户的入职日期,默认为当天
*/
document.getElementById('join').value = new Date().format("yyyy-MM-dd");
