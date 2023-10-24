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

    if(tabItemWidth <= tabsWidth)
    {
        $leftBtn.hide();
        $rightBtn.hide();
        $('#' + domID + ' > .nav-tabs')[0].style.transform = 'translateX(0px)';
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $leftBtn.hide();
    }

    if((tabItemWidth + distance) <= tabsWidth * 0.75)
    {
        $rightBtn.hide();
        return arrowTabs(domID, 1, true);
    }

    if(domID == 'monacoTabs' && distance < -60) distance = distance + 60;

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

/**
 * 获取tabs内容高度。
 * Get tab-content height.
 *
 * @access public
 * @return void
 */
function getIframeHeight()
{
    if(iframeHeight) return iframeHeight;

    var windowHeight   = $(window).height();
    var headerHeight   = parseInt($('#header').height());
    var mainNavbar     = parseInt($('#mainNavbar').height());
    var featureBar     = parseInt($('#featureBar').height());
    var tabsbar        = parseInt($('.nav-tabs').height());
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    featureBar         = featureBar ? featureBar : 0;
    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    iframeHeight       = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - tabsbar - 20 - featureBar;

    return iframeHeight;
}

/**
 * 获取左侧边栏高度。
 * Get sidebar height.
 *
 * @access public
 * @return void
 */
function getSidebarHeight()
{
    if(sidebarHeight) return sidebarHeight;

    var windowHeight   = $(window).height();
    var headerHeight   = parseInt($('#header').height());
    var mainNavbar     = parseInt($('#mainNavbar').height());
    var featureBar     = parseInt($('#featureBar').height());
    var repoMenu       = parseInt($('#repoBranchDropMenu').height());
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    repoMenu           = repoMenu ? repoMenu : 0;
    featureBar         = featureBar ? featureBar : 0;
    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    sidebarHeight  = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - repoMenu - featureBar;

    return sidebarHeight;
}


/**
 * 查找选中元素所有的父元素及选中元素的id。
 * Find parent nodes and item id of selected tree item.
 *
 * @access public
 * @return string
 */
function findItemInTreeItems(list, key, level) {
    for (const item of list) {
        if(level === 0)
        {
            parentTree = [item.key];
        }

        if (item.id === key) return item;

        if (item.children && item.children.length > 0)
        {
            const findedItem = findItemInTreeItems(item.children, key);
            if (findedItem)
            {
                parentTree.push(item.key);
                return findedItem;
            }
        }
    }
}

/**
 * 展开树状结构。
 * Expand tree node.
 *
 * @access public
 * @return void
 */
function expandTree()
{
    const treeObj = $('#monacoTree').parent().data('zui.Tree');
    if(treeObj == undefined || treeObj.$ == undefined) return;

    for (const key of parentTree) treeObj.$.expand(key);
}