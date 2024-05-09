#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('closedby')->gen(10);

/**

title=bugModel->getDataOfClosedBugsPerUser();
timeout=0
cid=1

- 获取 admin 关闭的 bug 数据
 - 第admin条的name属性 @admin
 - 第admin条的value属性 @10

*/

$bug = new bugTest();
r($bug->getDataOfClosedBugsPerUserTest()) && p('admin:name,value') && e('admin,10'); // 获取 admin 关闭的 bug 数据
