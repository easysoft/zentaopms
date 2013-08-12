$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
