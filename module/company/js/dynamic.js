$('#account, #product, #project, #execution, #orderBy').change(function()
{
    var userID      = $('#account').val();
    var productID   = $('#product').length ? $('#product').val() : 0;
    var projectID   = $('#project').val();
    var executionID = $('#execution').val();
    var orderBy     = $('#orderBy').val();

    browseType = browseType == 'bysearch' ? 'all' : browseType;
    link = createLink('company', 'dynamic', 'browseType=' + browseType + '&param=&recTotal=0&date=&direction=no&userID=' + userID + '&productID=' + productID + '&projectID=' + projectID + '&executionID=' + executionID + '&orderBy=' + orderBy);
    location.href = link;
})
