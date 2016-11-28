$(document).ready(function()
{
    setModal4List('runCase', 'caseList', function(){$(".iframe").modalTrigger({width:1024, type:'iframe'});}, 1024);
    if(browseType == 'bysearch') ajaxGetSearchForm();
    setTimeout(function(){fixedTfootAction('#casesForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#caseList')}, 100);

    var treeMaxHeight = document.documentElement.clientHeight - $('#header').height() - $('#featurebar').height() - $('#footer').height() - $('#casesbox .side-body .panel-heading').height() - 120;
    if($('#casesbox').height() > $('#caseList').height()) treeMaxHeight -= 20;
    $('#casesbox .side-body .panel-body').children().each(function(){if(!$(this).hasClass('tree')) treeMaxHeight -= $(this).height()});
    $('#casesbox .tree').css({'max-height':treeMaxHeight, 'overflow-y': 'auto', 'overflow-x':'hidden'});
    if(moduleID > 0)$('#casesbox .tree').scrollTop($('#casesbox .tree #module' + moduleID).offset().top - $('#casesbox .tree li[data-id="1"]').offset().top);
});
