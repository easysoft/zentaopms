$(function()
{
    $('input[name^="involved"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involved', involved, {expires: config.cookieLife, path: config.webRoot});
        window.location.reload();
    });

    $('[id="switchButton"]').click(function()
    {
        var projectType = $(this).attr('data-type');
        $.cookie('projectType', projectType, {expires: config.cookieLife, path: config.webRoot});
        window.location.reload();
    });

    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProjectBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

    if(!useDatatable) resetNameWidth();
});

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#projectForm .checkbox-primary').hide();
    if($.cookie('showProjectBatchEdit') == 1) $('#projectForm .checkbox-primary').show();
}

function resetNameWidth()
{
    $name = $('#projectForm thead th.c-name');
    if($name.width() < 350) $name.width(350);
}

$('#mainContent .sidebar-toggle').click(function()
{
    if(!useDatatable) setTimeout("resetNameWidth()", 100);
})

/**
 * Change program.
 *
 * @param  int    $programID
 * @access public
 * @return void
 */
function changeProgram(programID)
{
    link = createLink('project', 'browse', 'programID=' + programID + '&browseType=' + browseType + '&param=' + param + '&orderBy=order_asc&recTotal=' + recTotal + '&recPerPage=' + recPerPage + '&pageID=' + pageID);
    location.href = link;
}

$(".tree #program" + programID).parent('li').addClass('active');
