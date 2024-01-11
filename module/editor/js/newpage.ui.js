window.setHeight = function()
{
    codeHeight = parent.$('#editWin').height();
    $('.panel').height(codeHeight);
}

window.openInEditWin = function(url)
{
    parent.$('#editWin').attr('src', url);
};

setHeight();
window.addEventListener('resize', function()
{
    setHeight();
});
