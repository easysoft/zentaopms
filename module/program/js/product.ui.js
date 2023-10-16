/**
 * Submit data to product batch edit page by html form while click on the batch edit button.
 */
$(document).off('click', '[data-formaction]').on('click', '[data-formaction]', function()
{
    const $this       = $(this);
    const dtable      = zui.DTable.query($('#products'));
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const formData = new FormData();
    checkedList.forEach(function(id)
    {
        formData.append('productIDList[]', id);
    });

    postAndLoadPage($this.data('formaction'), formData);
});

window.footerSummary = function(checkedIdList, pageSummary)
{
    return {html: pageSummary};
}
