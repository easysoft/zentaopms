window.changeIsRequired = function(e)
{
    $('.selectable-rows').toggleClass('hidden', e.target.value == 0);
}
