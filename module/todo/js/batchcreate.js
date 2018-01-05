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

function switchDateList(number)
{
    if($('#switchDate' + number).attr('checked') == 'checked')
    {
        $('#begins' + number).attr('disabled', 'disabled').trigger('chosen:updated');
        $('#ends' + number).attr('disabled', 'disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begins' + number).removeAttr('disabled').trigger('chosen:updated');
        $('#ends' + number).removeAttr('disabled').trigger('chosen:updated');
    }
}
