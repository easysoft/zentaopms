<?php
include dirname(__FILE__) . '/lib/ui.php';

$tester = new tester();
$tester->login();
sleep(2);

/**
 * Get all xpath of main menu.
 *
 * @param  int    $count
 * @access public
 * @return array
 */
function getAppMenu($mainCount = 15)
{
    $xpath = '//*[@id="menuMainNav"]/li[%s]';

    $mainMenu = array();
    for($i = 1; $i <= $mainCount; $i ++)
    {
        $mainMenu[] = sprintf($xpath, $i);
    }

    return $mainMenu;
}

/**
 * Get all xpath of secondary menu.
 *
 * @param  int    $count
 * @access public
 * @return array
 */
function getMainNav($count = 20)
{
    $xpath = '//*[@id="navbar"]//li[%s]';

    $menus = array();
    for($i = 1; $i <= $count; $i ++)
    {
        $menus[] = sprintf($xpath, $i);
    }

    return $menus;
}

/**
 * Get all xpath of level3 menu.
 *
 * @param  int    $count
 * @access public
 * @return array
 */
function getSubNav($count = 10)
{
    $xpath = '//*[@id="subNavbar"]/ul/li[%s]';

    $menus = array();
    for($i = 1; $i <= $count; $i ++)
    {
        $menus[] = sprintf($xpath, $i);
    }

    return $menus;
}

/**
 * Get all xpath of more menu.
 *
 * @param  int    $count
 * @access public
 * @return array
 */
function getMoreMenu($count = 5)
{
    $xpath = '//*[@id="menuMoreList"]/li[%s]/a';

    $menus = array();
    for($i = 1; $i <= $count; $i ++)
    {
        $menus[] = sprintf($xpath, $i);
    }

    return $menus;
}

/**
 * Get all xpath of set menu.
 *
 * @param  int    $count
 * @access public
 * @return array
 */
function getSetMenu($count = 10)
{
    $xpath = '//*[@id="mainContent"]/div[1]/div[1]/div[2]/div[%s]';

    $menus = array();
    for($i = 1; $i <= $count; $i ++)
    {
        $menus[] = sprintf($xpath, $i);
    }

    return $menus;
}

/**
 * Filter link list.
 *
 * @param  array    $linkList
 * @param  string $type   get|pathinfo|''
 * @access public
 * @return object
 */
function filter($linkList, $type = '')
{
    $links = new stdclass;
    foreach($linkList as $link)
    {
        if(preg_match('/^$/', $link) !== 0) continue;
        if(strpos($link, 'javascript:') !== false) continue;
        if(strpos($link, 'index.php') === false) continue;
        if(strpos($link, '%') !== false) continue;

        if($type == 'get')
        {
            $queryString = str_replace('index.php?', '', $link);
            parse_str($queryString, $queryArray);
            if(!isset($queryArray['m'])) continue;
            $moduleName = $queryArray['m'];
            $methodName = strpos($queryArray['f'], '#') ? substr($queryArray['f'], 0, strpos($queryArray['f'], '#')) : $queryArray['f'];
        }

        if($type == 'pathinfo')
        {
            $urlParts = explode('-', $link);
            $moduleName = $urlParts[0];
            $methodName = strpos($urlParts[1], '#') ? substr($urlParts[1], 0, strpos($urlParts[1], '#')) : $urlParts[1];
        }

        if(!isset($links->$moduleName)) $links->$moduleName = new stdclass;
        if(!isset($links->$moduleName->$methodName)) $links->$moduleName->$methodName  = array();
        if(in_array($link, $links->$moduleName->$methodName)) continue;
        $links->$moduleName->$methodName[] = $link;
    }

    return $links;
}

/**
 * Save links to config file.
 *
 * @param  object $object
 * @param  string $name
 * @param  string $fileName
 * @access public
 * @return mixed
 */
function saveToConfig($object, $name = 'config', $fileName = 'result.php')
{
    $config = '';
    $config .= "<?php\n";
    $config .=  "\${$name} = new stdclass;\n";
    foreach($object as $module => $moduleObject)
    {
        $config .= "\${$name}->{$module} = new stdclass;\n";
        foreach($moduleObject as $method => $link)
        {
            foreach($link as $key => $value)
            {
                $methodName = $key == 0 ? $method : "{$method}_{$key}";
                $config .= "\${$name}->{$module}->{$methodName} = '{$value}';\n";
            }
        }
    }

    file_put_contents($fileName, $config);
}

/**
 * 点击第一级导航。
 * Click first nav.
 *
 * @param  string $menu
 * @param  object $page
 * @param  int    $waitTime
 * @access public
 * @return object|bool
 */
function clickFirstNAV($menu, $page, $waitTime = 2)
{
    if(strpos($menu, 'menuMainNav') == false) $page->dom->btn('更多')->click();

    $setMenu = '';
    if(strpos($menu, 'mainContent') !== false)
    {
        $setMenu = $menu;
        $menu    = '//*[@id="menuMoreList"]/li[@data-app="admin"]';
    }

    $menuElement = $page->dom->getElement($menu);
    $appName     = $menuElement->getText();

    if(!$appName) $appName = $menuElement->attr('class');
    if($appName == 'hidden') return false;

    $dataApp  = $menuElement->attr('data-app');
    $iframeID = 'appIframe-' . $dataApp;
    if($iframeID == 'admin' && (strpos($menu, 'mainContent') == false)) return false;
    if($iframeID == 'appIframe-') $iframeID = 'appIframe-admin';
    $menuElement->click();
    sleep($waitTime);
    echo "click 1级导航 {$menu}\n";

    $page->dom->switchToIframe($iframeID);
    if($setMenu)
    {
        $page->dom->getElement('//*[@id="heading"]/div[1]/a')->click();
        sleep($waitTime);
        $page->dom->getElement($setMenu)->click();
        sleep($waitTime);
    }

    $firstNav = new stdclass();
    $firstNav->iframeID = $iframeID;
    $firstNav->appName  = $appName;
    $firstNav->dataApp  = $dataApp;

    return $firstNav;
}

/**
 * 点击第二级导航。
 * Click second nav.
 *
 * @param  string $nav
 * @param  object $page
 * @param  int    $waitTime
 * @access public
 * @return void
 */
function clickSecondNav($nav, $page, $waitTime = 2)
{
    $navElement = $page->dom->getElement($nav);
    $navClass   = $navElement->attr('class');
    if($navClass == 'divider' || $navClass == 'nav-divider') return false;

    $navElement->click();
    echo "click 2级导航 {$nav}\n";
    sleep($waitTime);

    $navURL = $page->webdriver->getPageUrl();
    $url = trim(parse_url($navURL, PHP_URL_PATH), '/') . '?' . parse_url($navURL, PHP_URL_QUERY);
    if(parse_url($navURL, PHP_URL_FRAGMENT)) $url .= '#' . parse_url($navURL, PHP_URL_FRAGMENT);

    return $url;
}

/**
 * 点击第三级导航。
 * Click third nav.
 *
 * @param  string $subBar
 * @param  object $page
 * @param  int    $waitTime
 * @access public
 * @return void
 */
function checkThirdNav($subBar, $page, $waitTime = 2)
{
    try
    {
        $subBarElement = $page->dom->getElement($subBar);
        $subBarClass   = $subBarElement->attr('class');
    }
    catch(Exception $e)
    {
        $subBar = str_replace('//*[@id="subNavbar"]/ul', '//*[@id="mainNavbar"]/div/menu', $subBar);
        $subBarElement = $page->dom->getElement($subBar);
        $subBarClass = $subBarElement->attr('class');
    }

    if($subBarClass == 'divider') return false;
    $subBarElement->click();
    sleep($waitTime);
    echo "click 3级导航 {$subBar}\n";

    $subURL = $page->webdriver->getPageUrl();
    $url = trim(parse_url($subURL, PHP_URL_PATH), '/') . '?' . parse_url($subURL, PHP_URL_QUERY);
    if(parse_url($subURL, PHP_URL_FRAGMENT)) $url .= '#' . parse_url($subURL, PHP_URL_FRAGMENT);

    return $url;
}

/**
 * 检查重复的url。
 * Check duplicate url.
 *
 * @param  object $page
 * @param  string $nav
 * @param  string $url
 * @param  array  $linkList
 * @param  object $firstNav
 * @access public
 * @return string
 */
function checkDuplicateURL($page, $nav, $url, $linkList, $firstNav)
{
    if(!in_array($url, $linkList)) return $url;

    var_dump('重复的url：' . $url);
    $oldIframe = '//iframe[@name="app-' . $firstNav->dataApp . '-old"]';
    $page->dom->switchToIframe($oldIframe);
    $navElement = $page->dom->getElement($nav);
    $navElement->click();
    sleep(2);

    $navURL = $page->webdriver->getPageUrl();
    $url = trim(parse_url($navURL, PHP_URL_PATH), '/') . '?' . parse_url($navURL, PHP_URL_QUERY);
    if(parse_url($navURL, PHP_URL_FRAGMENT)) $url .= '#' . parse_url($navURL, PHP_URL_FRAGMENT);

    return $url;
}

/**
 * 收集链接。
 * Collect links.
 *
 * @param  object $page
 * @param  object $firstNav
 * @access public
 * @return array
 */
function getURLinNAV($page, $firstNav)
{
    $linkElements = $page->dom->getElementList('//a');
    if(!isset($linkElements->element) || empty($linkElements->element)) return array();
    $iframeID = $firstNav->iframeID;

    $linkList = array();
    $linkCount = 0;
    foreach($linkElements->element as $linkElement)
    {
        $url = $linkElement->getAttribute('href');
        if(!$url) continue;
        if(strpos($url, 'index.php') !== false) $url = str_replace('/index.php', 'index.php', $url);
        if(strpos($iframeID, 'appIframe-') !== false) $url .= "#app=" . substr($iframeID, strpos($iframeID, 'appIframe-') + strlen('appIframe-'));
        $linkList[] = $url;

        $linkCount++;
    }

    return $linkList;
}

$linkList = array();
$page = $tester->page;

$appMenu = array_merge(getAppMenu(15), getMoreMenu(10), getSetMenu(15));
foreach($appMenu as $menu)
{
    try
    {
        $firstNav = clickFirstNav($menu, $page);
        if(!$firstNav) continue;
    }
    catch(Exception $e)
    {
        continue;
    }

    $mainMenu = getMainNav(30);
    foreach($mainMenu as $nav)
    {
        $page->dom->switchToIframe('');
        $page->dom->switchToIframe($firstNav->iframeID);

        try
        {
            $navURL = clickSecondNav($nav, $page);
            if(!$navURL) continue;

            $navURL = checkDuplicateURL($page, $nav, $navURL, $linkList, $firstNav);
            if(!$navURL) continue;

            $linkList[] = $navURL;
            $urlList = getURLinNAV($page, $firstNav);

        }
        catch(Exception $e)
        {
            continue;
        }
    }

    $page->dom->switchToIframe();
}

$links = filter($linkList, 'get');
saveToConfig($links, 'link', dirname(__FILE__) . '/result.php');
$tester->closeBrowser();
