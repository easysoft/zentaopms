$(document).ready(function()
{
    $('#' + browseType + 'Tab').addClass('active'); 
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();
});
