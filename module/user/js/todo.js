function changeDate(date)
{
    link = createLink('user', 'todo', 'account=' + account + '&date=' + date);
    location.href=link;
}
