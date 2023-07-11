$(document).on('click', '.model-item', function()
{
    const link = $(this).data('url');
    if(!link) return false;

    zui.Modal.hide();

    openUrl(link, {'app': 'project'});
});
