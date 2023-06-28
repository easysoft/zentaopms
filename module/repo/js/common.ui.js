$(function()
{
    $('#log').on('click', '.btn-close', closeRelation);
});

/**
 * 切换仓库。
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
        $.cookie.set('projectMode', repoID, {expires:config.cookieLife, path:config.webRoot});
        repoID = 0;
    }

    if(method != 'settings') method ="browse";
    link = createLink(module, method, 'repoID=' + repoID);
    location.href=link;
}

/**
 * 切换分支。
 * Switch branch for git.
 *
 * @param  string $branchID
 * @access public
 * @return void
 */
function switchBranch(branchID)
{
    $.cookie.set('repoBranch', branchID, {expires:config.cookieLife, path:config.webRoot});
    $.cookie.set('repoRefresh', 1, {expires:config.cookieLife, path:config.webRoot});
    location.href=location.href;
}

var distance = 0;

/**
 * 左右切换关联信息。
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

    var hasParent = $('#' + domID + ' .btn-left').length;
    var $leftBtn  = hasParent ? $('#' + domID + ' .btn-left')  : $('.btn-left');
    var $rightBtn = hasParent ? $('#' + domID + ' .btn-right') : $('.btn-right');

    $leftBtn.show();
    $rightBtn.show();
    if(hideRightBtn) $rightBtn.hide();

    var tabItemWidth = 0;
    if($('#' + domID + ' > .nav-tabs')[0]) tabItemWidth = $('#' + domID + ' > .nav-tabs')[0].clientWidth;
    var tabsWidth    = $('#' + domID)[0].clientWidth;
    if($('#' + domID + ' .close-bugs').length) tabsWidth = tabsWidth * 0.7;

    if(tabItemWidth < tabsWidth)
    {
        $leftBtn.hide();
        $rightBtn.hide();
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $leftBtn.hide();
    }

    if((tabItemWidth + distance) < tabsWidth * 0.75)
    {
        $rightBtn.hide();
        return arrowTabs(domID, 1, true);
    }

    $('#' + domID + ' > .nav-tabs')[0].style.transform = 'translateX('+ distance +'px)';
}

/**
 * 关闭关联信息tab。
 * Close commit relations.
 *
 * @access public
 * @return void
 */
function closeRelation()
{
    $('#relationTabs ul li').remove();
    $('#relationTabs .tab-content .tab-pane').remove();
    $('.history').html('');
    $('#log').data('line', 0);
    $('#log').hide();

    $('#codeContainer').css('height', codeHeight);
    $('#related').css('height', 0);
};