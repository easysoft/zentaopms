function changeUser(account)
{
    link = createLink('company', 'dynamic', 'type=account&param=' + account);
    location.href = link;
}
