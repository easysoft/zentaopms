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
