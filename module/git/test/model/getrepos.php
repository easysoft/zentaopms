#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/git.class.php';
su('admin');

/**

title=测试gitModel->getRepos();
timeout=0
cid=1

- 获取版本库 @http://10.0.1.161:51080/api/v4/projects/42/repository/

*/

$git = new gitTest();

$tester->dao->update(TABLE_REPO)->set('synced')->eq(1)->where('id')->eq(1)->exec();
r($git->getRepos()) && p() && e('http://10.0.1.161:51080/api/v4/projects/42/repository/');     // 获取版本库