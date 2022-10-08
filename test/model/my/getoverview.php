#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getOverview();
cid=1
pid=1

projectTotal数据获取 >> 110
allConsumed数据获取 >> 6750
thisYearConsumed数据获取 >> 0

*/

$my = new myTest();

$projectTotal     = $my->getOverviewTest()->projectTotal;
$allConsumed      = $my->getOverviewTest()->allConsumed;
$thisYearConsumed = $my->getOverviewTest()->thisYearConsumed;

r($projectTotal)     && p() && e('110'); //projectTotal数据获取
r($allConsumed)      && p() && e('6750');//allConsumed数据获取
r($thisYearConsumed) && p() && e('0');   //thisYearConsumed数据获取