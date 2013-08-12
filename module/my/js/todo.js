function changeDate(date)
{
    date = date.replace(/\-/g, '');
    link = createLink('my', 'todo', 'type=' + date);
    location.href=link;
}

$(".colorbox").colorbox({width:960, height:550, iframe:true, transition:'none'});
