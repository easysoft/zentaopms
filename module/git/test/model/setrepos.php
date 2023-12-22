#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/git.class.php';
su('admin');

/**

title=测试gitModel->setRepos();
timeout=0
cid=1

- 设置版本库属性id @1

*/

$git = new gitTest();
$tester->dao->update(TABLE_REPO)->set('synced')->eq(1)->where('id')->eq(1)->exec();
r($git->setRepos()) && p('id') && e(1);     // 设置版本库