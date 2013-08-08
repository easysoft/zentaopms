/* Swtich to search module. */
function browseBySearch(active)
{
    $('#querybox').removeClass('hidden');
    $('.side').addClass('hidden');
    $('.divider').addClass('hidden');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').addClass('active');
    $('#bymoduleTab').removeClass('active');
}

$(document).ready(function()
{
    setModal4List('runCase', 'caseList', function(){$(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});});
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

$(document).ready(function() 
{
    $(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});
})
