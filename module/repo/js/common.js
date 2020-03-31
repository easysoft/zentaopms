/**
 * Swtich repo.
 * 
 * @param  int    $repoID 
 * @param  string $module 
 * @param  string $method 
 * @access public
 * @return void
 */
function switchRepo(repoID, module, method)
{
    if(typeof(eventKeyCode) == 'undefined') eventKeyCode = 0;
    if(eventKeyCode > 0 && eventKeyCode != 13) return false;

    /* The projec id is a string, use it as the project model. */
    if(isNaN(repoID))
    {
        $.cookie('projectMode', repoID, {expires:config.cookieLife, path:config.webRoot});
        repoID = 0;
    }

    if(method != 'settings') method ="browse";
    link = createLink(module, method, 'repoID=' + repoID);
    location.href=link;
}

/**
 * Switch branch for git.
 * 
 * @param  string $branchID 
 * @access public
 * @return void
 */
function switchBranch(branchID)
{
    $.cookie('repoBranch', branchID, {expires:config.cookieLife, path:config.webRoot});
    $.cookie('repoRefresh', 1, {expires:config.cookieLife, path:config.webRoot});
    location.href=location.href;
}

/**
 * Limit select two. 
 * @return void
 */
if($("input:checkbox[name='revision[]']:checked").length < 2)
{
    $("input:checkbox[name='revision[]']:lt(2)").attr('checked', 'checked');
}
$("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
$("input:checkbox[name='revision[]']").click(function(){
    var checkNum = $("input:checkbox[name='revision[]']:checked").length;
    if (checkNum >= 2) 
    {
        $("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
    }
    else
    {
        $("input:checkbox[name='revision[]']").each(function(){$(this).attr("disabled", false)});
    }
});

$(function()
{
    $(document).on('click', '.ajaxPager', function()
    {
        $('#sidebar .side-body').load($(this).attr('href'));
        return false;
    })

    if($('#sidebar').size() > 0)
    {
        var fixH = $("#sidebar").offset().top;
        $(window).scroll(function()
        {
            var scroH = $(this).scrollTop();
            if(scroH>=fixH)
            {
                $("#sidebar > .side-body").addClass('affix');
            }
            else if(scroH<fixH)
            {
                $("#sidebar > .side-body").removeClass('affix');
            }
        });
    }

    if($("main").is(".hide-sidebar"))
    {
        $(".sidebar-toggle").children().attr("class", "icon icon-angle-left");
    }
    else
    {
        $("#sidebar").bind("click", function ()
        {
            $(".sidebar-toggle").children().attr("class", "icon icon-angle-left");
            $(this).unbind();
        });
    }
})
