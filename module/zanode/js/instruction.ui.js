/**
 * 获取tabs内容高度。
 * Get tab-content height.
 *
 * @access public
 * @return void
 */
window.getIframeHeight = function()
{
    var windowHeight   = $(window).height();
    var headerHeight   = parseInt($('#header').height());
    var mainNavbar     = parseInt($('#mainNavbar').height());
    var featureBar     = parseInt($('#featureBar').height());
    var mainMenuHeight = parseInt($('#mainMenu').css('padding-top')) + parseInt($('#mainMenu').css('padding-bottom'));
    var appTabsHeight  = parseInt($('#appTabs').height());
    var appsBarHeight  = parseInt($('#appsBar').height());
    featureBar         = featureBar ? featureBar : 0;
    appsBarHeight      = appsBarHeight ? appsBarHeight : 0;
    appTabsHeight      = appTabsHeight ? appTabsHeight : 0;
    mainMenuHeight     = mainMenuHeight ? mainMenuHeight : 0;
    mainNavbar         = mainNavbar ? mainNavbar : 0;
    iframeHeight       = windowHeight - headerHeight - appsBarHeight - appTabsHeight - mainMenuHeight - mainNavbar - 20 - featureBar;

    $('#iframe-instruction').height(iframeHeight);
}
