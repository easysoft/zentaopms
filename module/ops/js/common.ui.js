window.removeItem = function()
{
    $(this).parent().parent().remove();
}

window.addItem = function(e)
{

    $(this).parent().parent().after(template);
}