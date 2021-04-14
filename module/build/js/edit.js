function loadExecutions()
{
    var productID = $('#product').val();
    var branchID  = $('#branch').length > 0 ? $('#branch').val() : 0;
    $('#executionsBox').load(createLink('product', 'ajaxGetExecutions', 'productID=' + productID + '&executionID=0&branch=' + branchID), function()
    {
        $('#executionsBox #execution').chosen().removeAttr('onchange');
    });
}

$(document).on('change', '#product,#branch', function()
{
    loadExecutions();
})
