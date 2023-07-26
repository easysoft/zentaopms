/**
 * Submit data to product batch edit page by html form while click on the batch edit button.
 */
window.onClickBatchEdit = function(event)
{
    const checkedList = zui.DTable.query(event.target).$.getChecks();

    if(!checkedList.length) return;

    const formData = new FormData();
    checkedList.forEach(function(id)
    {
        formData.append('productIDList[]', id);
    });

    postAndLoadPage($(event.target.closest('button')).data('url'), formData, '', {app: 'product'});
};
