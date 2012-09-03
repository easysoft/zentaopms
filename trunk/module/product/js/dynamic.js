function changeUser(account, productID)
{
    link = createLink('product', 'dynamic', 'productID=' + productID + '&type=account&param=' + account);
    location.href = link;
}
