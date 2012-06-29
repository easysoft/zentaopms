$(function()
{
    $('#' + browseType + 'Tab').addClass('active');
});

/* Browse by project. */
function browseByProject()
{
    $('#treebox').removeClass('hidden');
    $('.divider').removeClass('hidden');
    $('#querybox').addClass('hidden');
    $('#byProjectTab').addClass('active');
    $('#featurebar .active').removeClass('active');
}

