function changeUser(account, productID)
{
    if(account == '')
    {
        link = createLink('product', 'dynamic', 'productID=' + productID + '&type=all');
    }
    else
    {
        link = createLink('product', 'dynamic', 'productID=' + productID + '&type=account&param=' + account);
    }
    location.href = link;
}
