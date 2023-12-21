#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addAdminInviteField();
cid=1

- 判断需求的releasedDate是否更新成功,差值在10秒内 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';
zdTable('action')->config('action')->gen(10);
zdTable('story')->gen(10);
zdTable('product')->gen(10);
zdTable('release')->gen(10);

$upgrade = new upgradeTest();

$upgrade->processHistoryOfStory();
$date1 = strtotime(date('Y-m-d H:i:s', strtotime('-1 month')));
$date2 = strtotime(date('Y-m-d H:i:s', strtotime('-1 month +7 days')));

global $tester;
$storys = $tester->dao->select('releasedDate')->from(TABLE_STORY)->where('id')->in(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))->fetchAll();

$condition1 = ($date1 - strtotime($storys[0]->releasedDate)) < 10;
$condition2 = ($date2 - strtotime($storys[7]->releasedDate)) < 10;
r($condition1 && $condition2) && p('') && e(1);     //判断需求的releasedDate是否更新成功,差值在10秒内
