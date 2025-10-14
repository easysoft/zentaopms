ajaxInstallEvent('generate-config');
$(document).on('click', 'button[type="submit"]', function(e)
{
    ajaxInstallEvent('generate-config-next');
});
