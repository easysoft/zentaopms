function changeDate(date)
{
    location.href = createLink('user', 'todo', 'account=' + account + '&type=' + date.replace(/\-/g, ''));
}

$(function()
{
    $(".colorbox").modalTrigger({width:960, type:'iframe'});
});
