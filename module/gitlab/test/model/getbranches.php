#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getBranches();
timeout=0
cid=1

- 获取gitlab服务器1空项目id 1的项目分支。 @0
- 获取gitlab服务器1项目id 2的项目分支。 @branch1
- 获取gitlab服务器1项目id 2的项目分支数量。 @1
- 获取不存在gitlab服务器项目id 1的项目分支。 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$projectIds = array(1, 2);
$branches   = $gitlab->getBranchesTest($gitlabID = 1, $projectIds[1]);

r($gitlab->getBranchesTest($gitlabID = 1, $projectIds[0]))  && p('0') && e('0');       // 获取gitlab服务器1空项目id 1的项目分支。
r($branches)                                                && p('0') && e('branch1'); // 获取gitlab服务器1项目id 2的项目分支。
r(count($branches) > 2)                                     && p()    && e('1');          // 获取gitlab服务器1项目id 2的项目分支数量。
r($gitlab->getBranchesTest($gitlabID = 10, $projectIds[0])) && p('0') && e('0');       // 获取不存在gitlab服务器项目id 1的项目分支。