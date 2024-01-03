#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getCommits();
timeout=0
cid=1

- 获取gitlab服务器1空项目id 1的项目提交。 @0
- 获取gitlab服务器1项目id 2的项目提交。属性title @Initial template creation
- 获取gitlab服务器1项目id 2的项目提交数量。 @1
- public路径的commit比根目录路径commit数量少 @1
- 指定revision的commit查询属性title @2023-12-21

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$gitlab = new gitlabTest();

$repoIds      = array(1, 2, 3);
$commits      = $gitlab->getCommitsTest($repoIds[0]);
$entryCommits = $gitlab->getCommitsTest($repoIds[0], '/public');
$beginCommits = $gitlab->getCommitsTest($repoIds[0], '', null, '2023-12-21', '');

r($gitlab->getCommitsTest($repoIds[1]))   && p()        && e('0');          // 获取gitlab服务器1空项目id 1的项目提交。
r(end($commits))                          && p('title') && e('Initial template creation'); // 获取gitlab服务器1项目id 2的项目提交。
r(count($commits) > 1)                    && p()        && e('1');          // 获取gitlab服务器1项目id 2的项目提交数量。
r(count($entryCommits) < count($commits)) && p()        && e('1'); //public路径的commit比根目录路径commit数量少
r(end($beginCommits))                     && p('title') && e('2023-12-21'); //指定revision的commit查询