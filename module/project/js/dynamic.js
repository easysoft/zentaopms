function changeUser(account, projectID)
{
    link = createLink('project', 'dynamic', 'projectID=' + projectID + '&type=account&param=' + account);
    location.href = link;
}
