window.loadFormat = function()
{
    const hasVersion = $('#hasVersion').is(':checked');
    if(hasVersion)
    {
        $('#format').removeClass('hidden');
        $('#format').find('input').first().prop('checked', true);
    }
    else
    {
        $('#format').addClass('hidden');
        $('#format').find('input').prop('checked', false);
    }
}
