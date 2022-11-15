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

    /* The project id is a string, use it as the project model. */
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
        $('#submit').removeAttr('disabled');
    }
    else
    {
        $("input:checkbox[name='revision[]']").each(function(){$(this).attr("disabled", false)});
        $('#submit').attr('disabled', 'disabled');
    }
});

var distance = 0;

/**
 * Aarrow tabs area.
 *
 * @param  string domID
 * @param  number shift 1|-1
 * @param  bool   hideRightBtn
 * @access public
 * @return void
 */
function arrowTabs(domID, shift, hideRightBtn)
{
    if($('#' + domID).html() == '') return;

    $('.btn-right, .btn-left').show();
    if(hideRightBtn) $('.btn-right').hide();

    var tabItemWidth = $('#' + domID + ' > .tabs-navbar > .nav-tabs')[0].clientWidth;
    var tabsWidth    = $('#' + domID + '')[0].clientWidth;
    if(tabItemWidth < tabsWidth)
    {
        $('.btn-right, .btn-left').hide();
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $('.btn-left').hide();
    }

    if((tabItemWidth + distance) < tabsWidth * 0.75)
    {
        $('.btn-right').hide();
        return arrowTabs(domID, 1, true);
    }

    $('#' + domID + ' > .tabs-navbar > .tabs-nav')[0].style.transform = 'translateX('+ distance +'px)';
}

$(function()
{
    $(document).on('click', '.ajaxPager', function()
    {
        $('#sidebar .side-body').load($(this).attr('data-href'));
        return false;
    }).on('click', '#related .btn-close', function()
    {
        $('#codeContainer').css('height', codeHeight);
        $('#related').css('height', 0);
        $tabs = $('#relationTabs').data('zui.tabs');
        if($tabs) $tabs.closeAll();
    });

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
