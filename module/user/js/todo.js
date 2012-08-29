function changeDate(date)
{
    location.href = createLink('user', 'todo', 'account=' + account + '&type=' + date.replace(/\-/g, ''));
}
