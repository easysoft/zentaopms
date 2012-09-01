function changeDate(date)
{
    location.href = createLink('user', 'todo', 'account=' + account + '&type=' + date.replace(/\-/g, ''));
}

startDate = new Date(1970, 1, 1);
$(".todo-date").datePicker({createButton:true, startDate:startDate, maxDate:'%y-%M-%d'})
.dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_LEFT)
.bind('click', function() {
     $(this).dpDisplay();
     this.blur();
     return false;
});
