/* Browse by module. */
function browseByModule()
{
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#allTab').removeClass('active');
}

function browseBySearch()
{
    $('.divider').addClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#allTab').addClass('active');
}

$(function()
{
    if(browseType == 'bysearch') return;
    $('#pageActions ul.dropdown-menu').css('left', '67px');
    if(browseType == 'byediteddate' || browseType == 'openedbyme' || browseType == 'collectedbyme') 
    {
        $('#pageActions ul.dropdown-menu').css('left', '0px');
    }
    $('#' + browseType + 'Tab').addClass('active');
});
