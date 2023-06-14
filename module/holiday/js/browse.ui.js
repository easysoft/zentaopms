window.changeYear = function()
{
    const link = changeYearLink.replace('{year}', $(this).val());
    loadPage(link);
}
