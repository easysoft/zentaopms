#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->processHistoryOfStory().
cid=19542

- 判断需求的releasedDate是否更新成功,差值在10秒内 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';
zenData('action')->loadYaml('action')->gen(10);
zenData('story')->gen(10);
zenData('product')->gen(10);
zenData('release')->gen(10);

$upgrade = new upgradeTest();

$upgrade->processHistoryOfStory();
$date1 = strtotime(date('Y-m-d H:i:s', strtotime('-1 month')));
$date2 = strtotime(date('Y-m-d H:i:s', strtotime('-1 month +7 days')));

global $tester;
$storys = $tester->dao->select('releasedDate')->from(TABLE_STORY)->where('id')->in(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))->fetchAll();

$condition1 = ($date1 - strtotime($storys[0]->releasedDate)) < 10;
$condition2 = ($date2 - strtotime($storys[7]->releasedDate)) < 10;
r($condition1 && $condition2) && p() && e(1);     //判断需求的releasedDate是否更新成功,差值在10秒内
