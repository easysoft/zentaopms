window.setHeight = function()
{
    codeHeight = parent.$('#editWin').height();
    $('.panel').height(codeHeight);
}

setHeight();
window.addEventListener('resize', function()
{
    setHeight();
});
