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

function setBrowseType(type)
{
    $.cookie('browseType', type, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

$(function(){
    $('#' + browseType + 'Tab').addClass('active');
    if(fixedMenu) $('#modulemenu .nav li[data-id="custom' + libID + '"]').addClass('active');
    if(browseType == "bysearch")
    {
        ajaxGetSearchForm();
        browseBySearch();
    }
});
