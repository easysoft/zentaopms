function updateAction(date)
{
  if(date.indexOf('-') != -1)
  {
    var datearray = date.split("-");
    var date = '';
    for(i=0 ; i<datearray.length ; i++)
    {
      date = date + datearray[i];
    }
  }
  link = createLink('todo', 'batchCreate', 'date=' + date);
  location.href=link;
}

$(function(){
    var endDate = new Date('2222', '1', '1');
    $("#date").datePicker({
          createButton: true,
          startDate: new Date()
    }).dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_LEFT)
      .bind('click', function(){
      $(this).dpDisplay();
      this.blur();
      return false;
      });
})
