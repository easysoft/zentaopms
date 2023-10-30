window.afterRender = function()
{
    $('.editSnapshot').attr('onclick', "window.parent.editSnapshot('" + $('.editSnapshot').attr('href') + "')");
    $('.editSnapshot').attr('href', '###');
}
