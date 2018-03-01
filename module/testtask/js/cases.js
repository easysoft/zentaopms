$(document).ready(function()
{
    setModal4List('runCase', 'caseList', function(){$(".iframe").modalTrigger({width:1024, type:'iframe'});}, 1024);
    if(browseType == 'bysearch') ajaxGetSearchForm();
    setTimeout(function(){fixedTfootAction('#casesForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#caseList')}, 100);
    if($('#caseList thead th.w-title').width() < 150) $('#caseList thead th.w-title').width(150);
});
