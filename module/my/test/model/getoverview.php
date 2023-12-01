#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('project')->config('program')->gen('20');
zdTable('usergroup')->gen('10');
zdTable('group')->gen('10');
zdTable('bug')->gen('20');
zdTable('task')->gen('20');
zdTable('story')->gen('20');
zdTable('effort')->gen('1');
zdTable('user')->gen('1');

su('admin');
global $tester;
$tester->loadModel('program')->refreshStats(true);

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

r($projectTotal)     && p() && e('4'); // projectTotal数据获取
r($allConsumed)      && p() && e('3'); // allConsumed数据获取
r($thisYearConsumed) && p() && e('1'); // thisYearConsumed数据获取
