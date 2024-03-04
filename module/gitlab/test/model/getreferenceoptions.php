#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getReferenceOptions();
timeout=0
cid=1

- 获取gitlab服务器1空项目id 1的项目分支和标签。 @0
- 获取gitlab服务器1项目id 2的项目分支和标签。
 - 属性branch1 @Branch::branch1
 - 属性tag1 @Tag::tag1
- 获取gitlab服务器1项目id 2的项目分支数量。 @1
- 获取不存在gitlab服务器项目id 1的项目分支和标签。 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$projectIds = array(1, 2);
$branches   = $gitlab->getReferenceOptionsTest($gitlabID = 1, $projectIds[1]);

r($gitlab->getReferenceOptionsTest($gitlabID = 1, $projectIds[0]))  && p('0')            && e('0');                         // 获取gitlab服务器1空项目id 1的项目分支和标签。
r($branches)                                                        && p('branch1;tag1') && e('Branch::branch1,Tag::tag1'); // 获取gitlab服务器1项目id 2的项目分支和标签。
r(count($branches) > 4)                                             && p()               && e('1');                         // 获取gitlab服务器1项目id 2的项目分支数量。
r($gitlab->getReferenceOptionsTest($gitlabID = 10, $projectIds[0])) && p('0')            && e('0');                         // 获取不存在gitlab服务器项目id 1的项目分支和标签。