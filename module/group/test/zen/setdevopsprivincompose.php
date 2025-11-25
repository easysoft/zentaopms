#!/usr/bin/env php
<?php
/**

title=测试 groupModel->copy();
timeout=0
cid=16736

- 有历史数据设置DevOps权限第repo-import条的vision属性 @rnd
- 无历史数据DevOps权限个数 @5
- 没有历史数据DevOps权限个数 @4
- 没有历史数据设置DevOps权限第repo-import条的vision属性 @rnd
- 设置过滤的权限后的DevOps权限个数 @2
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';
su('admin');

$group = new groupZenTest();

zenData('pipeline')->gen(5);
r($group->setDevOpsPrivInComposeTest('manageRepo'))        && p('repo-import:vision') && e('rnd'); //有历史数据设置DevOps权限
r(count($group->setDevOpsPrivInComposeTest('manageRepo'))) && p() && e('5');                       //无历史数据DevOps权限个数

zenData('pipeline')->gen(0);
$config->group->setComposeDevOpsPriv['repo']['priv'] = 'repo-create';
r(count($group->setDevOpsPrivInComposeTest('manageRepo'))) && p() && e('4');                      //没有历史数据DevOps权限个数
r($group->setDevOpsPrivInComposeTest('manageRepo'))        && p('repo-import:vision') && e('rnd');//没有历史数据设置DevOps权限
$config->group->setComposeDevOpsPriv['repo']['priv'] = 'repo-create,repo-import,repo-edit';
r(count($group->setDevOpsPrivInComposeTest('manageRepo'))) && p() && e('2');                      //设置过滤的权限后的DevOps权限个数
