/* Browse by module. */
function browseByModule()
{
    $('.divider').removeClass('hidden');
    $('#bymoduleTab').addClass('active');
    $('#allTab').removeClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}

function browseBySearch()
{
    $('.divider').addClass('hidden');
    $('#bymoduleTab').removeClass('active');
    $('#allTab').addClass('active');
    $('#bysearchTab').addClass('active');
    $('#querybox').addClass('show');
}

$(function(){
    $('#' + browseType + 'Tab').addClass('active');
    if(browseType == "bysearch")
    {
        ajaxGetSearchForm();
        browseBySearch();
    }
});
