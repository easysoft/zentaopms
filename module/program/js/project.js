$(function()
{
    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProjectBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();
});

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#projectsForm .checkbox-primary').hide();
    if($.cookie('showProjectBatchEdit') == 1) $('#projectsForm .checkbox-primary').show();
}
