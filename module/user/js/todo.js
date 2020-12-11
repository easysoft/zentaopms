function changeDate(date)
{
    location.href = createLink('user', 'todo', 'userID=' + userID + '&type=' + date.replace(/\-/g, ''));
}

$(function()
{
    $(".colorbox").modalTrigger({width:960, type:'iframe'});
});
