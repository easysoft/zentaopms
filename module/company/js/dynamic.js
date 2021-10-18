$('#account, #product, #project, #execution').change(function()
{
    var userID      = $('#account').val();
    var productID   = $('#product').val();
    var projectID   = systemMode == 'new' ? $('#project').val() : 0;
    var executionID = $('#execution').val();

    browseType = browseType == 'bysearch' ? 'all' : browseType;
    link = createLink('company', 'dynamic', 'browseType=' + browseType + '&param=&recTotal=0&date=&direction=next&userID=' + userID + '&productID=' + productID + '&projectID=' + projectID + '&executionID=' + executionID);
    location.href = link;
})
