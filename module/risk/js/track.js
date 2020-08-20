function refreshPage(obj)
{
    var value = obj.value;
    if(value == 0)
    {
        $('.track').addClass('hidden');
        $('.not-track').removeClass('hidden');
    }
    else
    {
        $('.not-track').addClass('hidden');
        $('.track').removeClass('hidden');
    }
}
