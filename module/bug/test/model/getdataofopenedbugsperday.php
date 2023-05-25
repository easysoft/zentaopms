#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

zdTable('bug')->gen(10);

/**

title=bugModel->getDataOfOpenedBugsPerDay();
timeout=0
cid=1

- 获取创建的数据第0条的value属性 @1

*/

$bug = new bugTest();
r($bug->getDataOfOpenedBugsPerDayTest()) && p('0:value') && e('1'); //获取创建的数据