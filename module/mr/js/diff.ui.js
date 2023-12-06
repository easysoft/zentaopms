var iframeHeight  = 0;
var sidebarHeight = 0;
var tabTemp;
var diffAppose = $.cookie.get('renderSideBySide') == 'true';

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

    var windowHeight       = $(window).height();
    var headerHeight       = parseInt($('#header').height());
    var mainNavbar         = parseInt($('#navbar').height());
    var tabsbar            = parseInt($('.nav-tabs').height());
    var mainMenuHeight     = parseInt($('#mainContent').css('padding-top')) + parseInt($('#mainContent').css('padding-bottom'));
    var detailHeaderHeight = parseInt($('.detail-header').height());
    var mrMenuHeight       = parseInt($('#mrMenu').height());
    var appTabsHeight      = parseInt($('#appTabs').height());
    var appsBarHeight      = parseInt($('#appsBar').height());

    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    iframeHeight       = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - tabsbar - detailHeaderHeight - mrMenuHeight - 28;

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

    var windowHeight       = $(window).height();
    var headerHeight       = parseInt($('#header').height());
    var mainNavbar         = parseInt($('#navbar').height());
    var mainMenuHeight     = parseInt($('#mainContent').css('padding-top')) + parseInt($('#mainContent').css('padding-bottom'));
    var appTabsHeight      = parseInt($('#appTabs').height());
    var appsBarHeight      = parseInt($('#appsBar').height());
    var detailHeaderHeight = parseInt($('.detail-header').height());
    var mrMenuHeight       = parseInt($('#mrMenu').height());

    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    sidebarHeight  = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - detailHeaderHeight - mrMenuHeight - 20;

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
    if(!tabsEle.children().length) $('.monaco-dropmenu').addClass('hidden');
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
        $('#tab-' + fileAsId).html("<iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetDiffEditorContent', urlParams.replace('%s', fileAsId)) + "' width='100%' height='" + height + "' scrolling='no'></iframe>")

        /* Select default tree item. */
        const currentElement = findItemInTreeItems(tree, fileAsId, 0);
        if(currentElement != undefined) $('#' + currentElement.id).parent().addClass('selected');
        expandTree();

        $('.btn-left').on('click', function()  {arrowTabs('monacoTabs', 1);});
        $('.btn-right').on('click', function() {arrowTabs('monacoTabs', -2);});
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
    arrowTabs('monacoTabs', -2);
}

/**
 *  Change code encoding.
 *
 * @param  string encoding
 * @access public
 * @return void
 */
function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}

/**
 *  Html code decode.
 *
 * @param  string str
 * @access public
 * @return string
 */
function htmlspecialchars_decode(str){
    str = str.replace(/&amp;/g, '&');
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, "''");
    str = str.replace(/&#039;/g, "'");
    return str;
}

/**
 * Get diffs by file name.
 *
 * @param  string fileName
 * @access public
 * @return object
 */
window.getDiffs = function(fileName)
{
    if(fileName.indexOf('./') === 0) fileName = fileName.substring(2);

    var result = {
        'code': {'new': '', 'old': ''},
        'line': {'new': [], 'old': []}
    };
    $.each(diffs, function(i, diff)
    {
        if(diff.fileName == fileName)
        {
            if(!diff.contents || typeof diff.contents[0].lines != 'object') return result;

            $.each(diff.contents, function(k, content)
            {
                var lines = content.lines;
                $.each(lines, function(l, code)
                {
                    if(code.type == 'all' || code.type == 'new')
                    {
                        result.code.new += htmlspecialchars_decode(code.line.substring(1)) + "\n";
                        result.line.new.push(parseInt(code.newlc));
                    }

                    if(code.type == 'all' || code.type == 'old')
                    {
                        result.code.old += htmlspecialchars_decode(code.line.substring(1)) + "\n";
                        result.line.old.push(parseInt(code.oldlc));
                    }
                })
            })
            return result;
        }
    });

    return result;
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
    $('#monacoTabs .tab-content').append("<div id='" + eleId + "' class='tab-pane active in'><iframe class='repo-iframe' src='" + $.createLink('repo', 'ajaxGetDiffEditorContent', urlParams.replace('%s', entry)) + "' width='100%' height='" + height + "' scrolling='no'></iframe></div>")

    if($('.monaco-dropmenu').attr('class').indexOf('hidden')) $('.monaco-dropmenu').removeClass('hidden');
    setTimeout(() => {
        $("a[href='" + '#' + eleId + "']").trigger('click');
        updateEditorInline('#' + eleId);
    }, 100);
}

function updateEditorInline(eleId)
{
    $.cookie.set('renderSideBySide', diffAppose, {expires:config.cookieLife, path:config.webRoot});
    if(typeof  $(eleId + ' iframe')[0].contentWindow.updateEditorInline == 'function')
    {
        $(eleId + ' iframe')[0].contentWindow.updateEditorInline(diffAppose);
    }
}

$('#monacoTabs .nav-item a').on('click', function()
{
    var eleId = $(this).attr('href');
    $(eleId + ' iframe')[0].contentWindow.updateEditorInline(diffAppose);
});

$(document).ready(function()
{
    if(diffAppose)
    {
        $('.dropdown-menu #inline').show();
        $('.dropdown-menu #appose').hide();
    }
    else
    {
        $('.dropdown-menu #appose').show();
        $('.dropdown-menu #inline').hide();
    }

    $('.btn-left').on('click', function()  {arrowTabs('monacoTabs', 1);});
    $('.btn-right').on('click', function() {arrowTabs('monacoTabs', -2);});
});

$('.inline-appose').on('click', function()
{
    $('.inline-appose').hide();
    diffAppose = !diffAppose;
    if(diffAppose)
    {
        $('.dropdown-menu #inline').show();
    }
    else
    {
        $('.dropdown-menu #appose').show();
    }
    var tabID = $('#monacoTabs .nav-item .active').attr('href');
    updateEditorInline(tabID);
    return;
});

$(".label-exchange").on('click', function()
{
    var newDiffLink = diffLink.replace('{oldRevision}', newRevision);
    newDiffLink     = newDiffLink.replace('{newRevision}', oldRevision);
    openUrl(newDiffLink);
});

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
