var iframeHeight  = 0;
var sidebarHeight = 0;
var tabTemp;
var parentTree = [];

/* Close tab. */
$('#monacoTabs').on('click', '.monaco-close', function()
{
    var eleId    = $(this).parent().attr('href');
    var tabsEle  = $(this).parent().parent().parent();
    var isActive = $(this).parent().hasClass('active');

    $(this).parent().parent().remove();
    $(eleId).remove();
    $('#' + eleId.substring(5)).parent().removeClass('selected');
    if(isActive) tabsEle.children().last().find('a').trigger('click');
});

window.afterPageUpdate = function()
{
    setTimeout(function()
    {
        var fileAsId = file.replace(/=/g, '-');
        /* Resize moaco height. */
        $('#monacoTree').css('height', getSidebarHeight() - 8 + 'px');
        /* Init tab template. */
        if(!tabTemp) tabTemp = $('#monacoTabs ul li').first().clone();
        
        /* Load default tab content. */
        var height = getIframeHeight();
        $('#tab-' + fileAsId).html("<iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetEditorContent', urlParams.replace('%s', fileAsId)) + "' width='100%' height='" + height + "' scrolling='no'></iframe>")
        
        /* Select default tree item. */
        const currentElement = findItemInTreeItems(tree, fileAsId, 0);
        $('#' + currentElement.id).parent().addClass('selected');
        expandTree();
    }, 200);
};


/**
 * 点击左侧菜单打开详情tab。
 * Open new tab when click tree item.
 *
 * @access public
 * @return void
 */
window.treeClick = function(info)
{
    if (info.item.items && info.item.items.length > 0) return;
    $('#' + info.item.id).parent().addClass('selected');
    openTab(info.item.key, info.item.text);
}

/**
 * 打开新tab。
 * Open new tab.
 *
 * @access public
 * @return void
 */
function openTab(entry, name)
{
    var eleId   = 'tab-' + entry.replace(/=/g, '-');
    var element = document.getElementById(eleId);
    if (element)
    {
        $("a[href='" + '#' + eleId + "']").trigger('click');
        return;
    }

    var newTab = tabTemp.clone();
    newTab.find('a').attr('href', '#' + eleId);
    newTab.find('span').text(name);
    $('#monacoTabs .nav-tabs').append(newTab);

    var height = getIframeHeight();
    $('#monacoTabs .tab-content').append("<div id='" + eleId + "' class='tab-pane active in'><iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetEditorContent', urlParams.replace('%s', entry)) + "' width='100%' height='" + height + "' scrolling='no'></iframe></div>")

    setTimeout(() => {
        $("a[href='" + '#' + eleId + "']").trigger('click');
    }, 100);
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
        else
        {
            parentTree.push(item.key);
        }

        if (item.key === key) return item;

        if (item.items && item.items.length > 0)
        {
            const findedItem = findItemInTreeItems(item.items, key);
            if (findedItem) return findedItem;
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

    for (const key of parentTree) treeObj.$.expand(key);
}

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
    var tabsbar        = parseInt($('.nav-tabs').height());
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    iframeHeight       = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - tabsbar - 8;

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
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    sidebarHeight  = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar;

    return sidebarHeight;
}

/**
 * 在当前页面用modal加载链接。
 * Load link object page.
 *
 * @param  string $link
 * @access public
 * @return void
 */
window.loadLinkPage = function(link)
{
    $('#linkObject').attr('href', link);
    $('#linkObject').trigger('click');
}