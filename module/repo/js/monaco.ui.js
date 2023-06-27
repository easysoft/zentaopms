var iframeHeight  = 0;
var sidebarHeight = 0;
var tabTemp;
var parentTree = [];

/* Close tab. */
$('#monacoTabs').on('click', '.monaco-close', function()
{
    var eleId = $(this).parent().attr('href');
    if($(this).parent().hasClass('active')) $(this).parent().parent().parent().children().first().find('a').trigger('click');
    $(this).parent().parent().remove();
    $(eleId).remove();
    $('#' + eleId.substring(5)).parent().removeClass('selected');
});

window.afterPageUpdate = function()
{
    setTimeout(function()
    {
        /* Resize moaco height. */
        $('#monacoTree').css('height', getSidebarHeight() - 8 + 'px');
        /* Init tab template. */
        if(!tabTemp) tabTemp = $('#monacoTabs ul li').first().clone();
        
        /* Load default tab content. */
        var height = getIframeHeight();
        $('#tab-' + file).html("<iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetEditorContent', urlParams.replace('%s', file)) + "' width='100%' height='" + height + "' scrolling='no'></iframe>")
        
        /* Select default tree item. */
        const currentElement = findItemInTreeItems(tree, file, 0);
        $('#' + currentElement.id).parent().addClass('selected');
        expandTree();
    }, 100);
};


/**
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
 * Expand tree node.
 *
 * @access public
 * @return void
 */
function expandTree()
{
    const treeObj = $('#monacoTree').parent().data('zui.Tree');

    for (const key of parentTree)
    {
        treeObj.$.expand(key);
    }
}

/**
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
    iframeHeight       = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - tabsbar;

    return iframeHeight;
}

/**
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
