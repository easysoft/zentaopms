#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('project')->loadYaml('program')->gen('20');
zenData('usergroup')->gen('10');
zenData('group')->gen('10');
zenData('bug')->gen('20');
zenData('task')->gen('20');
zenData('story')->gen('20');
zenData('effort')->gen('1');
zenData('user')->gen('1');

su('admin');
global $tester;
$tester->loadModel('program')->refreshStats(true);

/**

title=测试 myModel->getOverview();
cid=1
pid=1

projectTotal数据获取 >> 4
allConsumed数据获取 >> 33
thisYearConsumed数据获取 >> 1

*/

$my = new myTest();

$projectTotal     = $my->getOverviewTest()->projectTotal;
$allConsumed      = $my->getOverviewTest()->allConsumed;
$thisYearConsumed = $my->getOverviewTest()->thisYearConsumed;

r($projectTotal)     && p() && e('4');  // projectTotal数据获取
r($allConsumed)      && p() && e('33'); // allConsumed数据获取
r($thisYearConsumed) && p() && e('1');  // thisYearConsumed数据获取
