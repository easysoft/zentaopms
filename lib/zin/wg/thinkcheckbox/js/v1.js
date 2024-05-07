window.changeIsRequired = function(e)
{
    if(Number(e.target.value))
    {
        $('.selectable-rows').removeClass('hidden');
    }
    else
    {
        $('.selectable-rows').addClass('hidden');
    }
}
