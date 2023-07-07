function getCreateForm(event)
{
    const url = $.createLink('my', 'manageContacts', 'listID=&mode=new');
    loadPage(url, '#dataForm');
}

function getEditForm(event)
{
    const listID = '3';
    const url    = $.createLink('my', 'manageContacts', 'listID=' + listID + '&mode=edit');
    loadPage(url, '#dataForm');
}
