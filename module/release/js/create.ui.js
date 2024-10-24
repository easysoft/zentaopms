function changeProduct(event)
{
    let productID = $(event.target).val();
    loadPage($.createLink('projectrelease', 'create', 'projectID=' + projectID + '&' + 'productID=' + productID));
}

window.changeStatus = function(e)
{
    const status = e.target.value;
    if(status == 'normal')
    {
        $('#releasedDate').closest('.form-row').removeClass('hidden');
        $('[data-name=date] .form-label').removeClass('required');
    }
    else
    {
        $('#releasedDate').closest('.form-row').addClass('hidden');
        $('[data-name=date] .form-label').addClass('required');
    }
}
