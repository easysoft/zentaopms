function changeUser(account, projectID)
{
    if(account == '')
    {
        link = createLink('project', 'dynamic', 'projectID=' + projectID + '&type=all');
    }
    else
    {
        link = createLink('project', 'dynamic', 'projectID=' + projectID + '&type=account&param=' + account);
    }
    location.href = link;
}
