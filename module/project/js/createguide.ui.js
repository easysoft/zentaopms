window.openCreateProjectLink = function($target, appTab, isInModal)
{
    const link = $target.data('url');
    if(!link) return false;

    zui.Modal.hide();

    openUrl(link, {'app': appTab});
};

$(function()
{
    if(!isInModal)
    {
        if(screen.width <= 1366)
        {
            $('.m-project-createguide #mainContainer').css('background', '#fff').css('min-width', '800px').width('800px');
        }
        else
        {
            $('.m-project-createguide #mainContainer').css('background', '#fff').css('min-width', '1000px').width('1000px');
        }
    }
})
