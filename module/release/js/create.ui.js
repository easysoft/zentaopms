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

window.setSystemBox = function(e)
{
    const newSystem = $(e.target).is(':checked') ? 1 : 0;
    $('#systemBox #systemName').addClass('hidden');
    $('#systemBox .picker-box').addClass('hidden');
    if(newSystem == 1)
    {
        $('#systemBox #systemName').removeClass('hidden');
    }
    else
    {
        $('#systemBox #systemName').val('');
        $('#systemBox .picker-box').removeClass('hidden');
    }
}
