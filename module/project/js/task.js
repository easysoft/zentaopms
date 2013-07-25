$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

function changeAction(formName, actionName, actionLink)
{
    if(actionName == 'batchClose') $('#' + formName).attr('target', 'hiddenwin');
    $('#' + formName).attr('action', actionLink).submit();
}
$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');
