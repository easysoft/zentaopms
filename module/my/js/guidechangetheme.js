$(function()
{
    setTimeout(function()
    {
        var link = createLink('my', 'guideChangeTheme', 'saveSkipUser=true');
        $.get(link);
    }, 500)
})
