function createContact()
{
    $('#contactTab').find('.contact').removeClass('active');
    const url    = $.createLink('my', 'manageContacts');
    loadPage(url, '#contactPanel');
}

function getContact(event)
{
    $('#contactTab').find('.contact').removeClass('active');
    const $li    = $(event.target).addClass('active');
    const listID = $li.data('id');
    const url    = $.createLink('my', 'manageContacts', 'listID=' + listID);
    loadPage(url, '#contactPanel');
}
