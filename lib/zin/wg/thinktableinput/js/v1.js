window.changeSupportAdd = function(e)
{
    if(Number(e.target.value))
    {
        $('.can-add-rows').removeClass('hidden');
    }
    else
    {
        $('.can-add-rows').addClass('hidden');
    }
}
window.changeIsRequired = function(e)
{
    if(Number(e.target.value))
    {
        $('.required-rows').removeClass('hidden');
    }
    else
    {
        $('.required-rows').addClass('hidden');
    }

}
window.changeInput = function(e)
{
    const value = $(this).val();
    const intValue = parseInt(value, 10);
    if(isNaN(intValue) || intValue < 1)
    {
      $(this).val(1);
    }
    else
    {
        $(this).val(intValue);
    }
}
