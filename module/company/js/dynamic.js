$('#account, #product, #project, #execution').change(function()
{
    var userID    = $('#account').val();
    var product   = $('#product').val();
    var project   = systemMode == 'new' ? $('#project').val() : 0;
    var execution = $('#execution').val();
    browseType    = browseType == 'bysearch' ? 'all' : browseType;
    link = createLink('company', 'dynamic', 'browseType=' + browseType + '&param=&recTotal=0&date=&direction=next&userID=' + userID + '&product=' + product + '&project=' + project + '&execution=' + execution);
    location.href = link;
})
