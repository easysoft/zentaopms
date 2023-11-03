window.changeColumnLimit = function()
{
    const noLimit = $('[name=noLimit]:checked').val();
    if(noLimit)
    {
        $('[name=limit]').val('').attr('disabled', true);
    }
    else
    {
        $('[name=limit]').removeAttr('disabled');
    }
}
