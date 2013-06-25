/* Switch to module browse. */
function browseByModule(active)
{
    $('.side').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#' + active + 'Tab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').addClass('hidden');
}

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

function changeAction(url)
{
  $('#batchForm').attr('action', url);
}

$(document).ready(function()
{
    loadColorbox('runCase', 'caseList');
    $('#' + browseType + 'Tab').addClass('active');
    $('#module' + moduleID).addClass('active'); 
    if(browseType == 'bysearch') ajaxGetSearchForm();
});

$(document).ready(function() 
{
    $(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});
})
