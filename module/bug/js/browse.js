$(function()
{
    if($('#bugList thead th.c-title').width() < 150) $('#bugList thead th.c-title').width(150);

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();
});
