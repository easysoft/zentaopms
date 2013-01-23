function showClearButton()
{
    if($('#sure').val() == 'yes')
    {
        $('#submit').removeClass('hidden');
        $('.a-center span').addClass('hidden');
    }
}
