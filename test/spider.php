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
