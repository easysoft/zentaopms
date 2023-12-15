#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getList();
timeout=0
cid=1

- 获取合并请求列表数量 @8
- 获取合并请求列表ID为1的合并请求的主机ID和标题
 - 第1条的hostID属性 @1
 - 第1条的title属性 @test-merge
- 获取合并请求列表数量 @3
- 获取开放中的合并请求列表的标题第7条的title属性 @test-merge7
- 获取合并请求列表数量 @2
- 获取指派给user1的合并请求列表的标题第6条的title属性 @test-merge6
- 获取合并请求列表数量 @2
- 获取创建者为user2的合并请求列表的标题第3条的title属性 @test-merge3
- 获取合并请求列表数量 @1
- 获取仓库ID为1的合并请求列表的标题第1条的title属性 @test-merge
- 获取合并请求列表数量 @1
- 获取执行ID为4的合并请求列表的标题第4条的title属性 @test-merge4
- 获取服务器ID是1，项目ID是5的合并请求列表数量 @0
- 获取服务器ID是1，项目ID是3的合并请求列表数量 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
zdTable('mr')->config('mr')->gen(10);
su('admin');

$mrModel = $tester->loadModel('mr');

$mode     = 'all';
$param    = 'all';
$orderBy  = 'id_desc';
$projects = array();
$repoID   = 0;
$objectID = 0;

$result = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()                 && e('8');            // 获取合并请求列表数量
r($result)        && p('1:hostID,title') && e('1,test-merge'); // 获取合并请求列表ID为1的合并请求的主机ID和标题

$mode   = 'status';
$param  = 'opened';
$result = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()          && e('3');           // 获取合并请求列表数量
r($result)        && p('7:title') && e('test-merge7'); // 获取开放中的合并请求列表的标题

$mode   = 'assignee';
$param  = 'user1';
$result = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()          && e('2');           // 获取合并请求列表数量
r($result)        && p('6:title') && e('test-merge6'); // 获取指派给user1的合并请求列表的标题

$mode   = 'creator';
$param  = 'user2';
$result = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()          && e('2');           // 获取合并请求列表数量
r($result)        && p('3:title') && e('test-merge3'); // 获取创建者为user2的合并请求列表的标题

$mode    = 'all';
$repoID  = 1;
$result  = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()          && e('1');          // 获取合并请求列表数量
r($result)        && p('1:title') && e('test-merge'); // 获取仓库ID为1的合并请求列表的标题

$repoID   = 0;
$objectID = 4;
$result   = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p()          && e('1');           // 获取合并请求列表数量
r($result)        && p('4:title') && e('test-merge4'); // 获取执行ID为4的合并请求列表的标题

/* Set user is not a adminer. */
global $app;
$app->user->admin = false;

$objectID = 0;
$projects = array(1 => 5);
$result   = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p() && e('0');           // 获取服务器ID是1，项目ID是5的合并请求列表数量

$projects = array(1 => 3);
$result   = $mrModel->getList($mode, $param, $orderBy, $projects, $repoID, $objectID);
r(count($result)) && p() && e('1');           // 获取服务器ID是1，项目ID是3的合并请求列表数量