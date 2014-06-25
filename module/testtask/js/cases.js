$(document).ready(function()
{
    setModal4List('runCase', 'caseList', function(){$(".iframe").modalTrigger({width:1024, type:'iframe'});}, 1024);
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();
});
