function changeDate(date)
{
    location.href = createLink('user', 'todo', 'userID=' + userID + 'from=' + from + '&type=' + date.replace(/\-/g, ''));
}

$(function()
{
    $(".colorbox").modalTrigger({width:960, type:'iframe'});
});
