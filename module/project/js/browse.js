$(function()
{
    $('input[name^="involved"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involved', involved, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('[id="switchButton"]').click(function()
    {
        var projectType = $(this).attr('data-type');
        $.cookie('projectType', projectType, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    if(!useDatatable) resetNameWidth();
});

function resetNameWidth()
{
    $name = $('#projectForm thead th.c-name');
    if($name.width() < 150) $name.width(150);
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
