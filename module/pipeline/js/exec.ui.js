window.closeModal = function(e)
{
    let   $modal  = $(e.target).closest('.modal');
    const modalID = $modal.attr('id');
    zui.Modal.hide('#' + modalID);
}
