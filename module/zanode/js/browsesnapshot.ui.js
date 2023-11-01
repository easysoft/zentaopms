window.afterRender = function()
{
    $('.editSnapshot').each(function()
    {
        $(this).attr('onclick', "window.parent.editSnapshot('" + $(this).attr('href') + "')");
        $(this).attr('href', '###');
    });
}
