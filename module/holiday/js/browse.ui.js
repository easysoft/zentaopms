window.changeYear = function()
{
    const link = changeYearLink.replace('{year}', $(this).find('[name="year"]').val());
    loadPage(link);
}
