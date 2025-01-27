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
 * @return mixed
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
 * @return mixed
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
 * @return mixed
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
 * @return void
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
 * @return void
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
}
