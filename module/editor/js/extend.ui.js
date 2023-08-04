window.openInEditWin = function(e)
{
    let url = $(e.target).data('url');
    if(typeof url == 'undefined') url = $(e.target).closest('button[data-call="openInEditWin"]').data('url');

    parent.$('#editWin').attr('src', url);
};

window.setHeight = function()
{
    codeHeight = parent.$('#extendWin').height();
    $('.panel').height(codeHeight);
}

parent.$('#extendWin').attr('data-url', location.href);
setHeight();
window.addEventListener('resize', function()
{
    setHeight();
});
