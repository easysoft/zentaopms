function changeUser(account)
{
    link = createLink('company', 'dynamic', 'type=account&param=' + account);
    location.href = link;
}
function changeProject(project)
{
    link = createLink('company', 'dynamic', 'type=project&param=' + project);
    location.href = link;
}
function changeProduct(product)
{
    link = createLink('company', 'dynamic', 'type=product&param=' + product);
    location.href = link;
}
