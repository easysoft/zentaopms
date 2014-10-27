$(function()
{
    setOuterBox();
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

$('#module' + moduleID).addClass('active');
$('#product' + productID).addClass('active');

$('table.datatable').datatable({
    customizable: false, 
    checkable: false,
    sortable: false,
    fixedLeftWidth: '450px',
    fixedRightWidth: '150px'
});
