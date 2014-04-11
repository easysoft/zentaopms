function showClearButton()
{
    if($('#sure').val() == 'yes')
    {
        $('#submit').removeClass('hidden');
        $('.input-group').addClass('hidden');
    }
}
