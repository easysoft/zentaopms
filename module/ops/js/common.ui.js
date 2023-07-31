window.removeItem = function()
{
    $(this).parent().parent().remove();
}

window.addItem = function(e)
{

    console.log(111, e);
    $(this).parent().parent().after(template);
}