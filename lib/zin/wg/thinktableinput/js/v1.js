window.changeSupportAdd = function(e)
{
    $('.can-add-rows').toggleClass('hidden', e.target.value == 0);
}
window.changeIsRequired = function(e)
{
    $('.required-rows').toggleClass('hidden', e.target.value == 0);
}
window.changeInput = function(e)
{
    const value = $(this).val();
    const intValue = value < 1 ? 1 : parseInt(value, 10);
    $(this).val(intValue);
}
