#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('closeddate')->gen(10);

/**

title=bugModel->getDataOfClosedBugsPerDay();
timeout=0
cid=1

- 获取每天关闭 bug 的数据第0条的value属性 @10

*/

$bug = new bugTest();
r($bug->getDataOfClosedBugsPerDayTest()) && p('0:value') && e('10'); // 获取每天关闭 bug 的数据