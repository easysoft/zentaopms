#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->update();
cid=1
pid=1

测试修改分支名称 >> name,分支1,修改后的分支1
测试修改分支状态 >> status,active,closed
测试修改分支描述 >> desc,,修改后的分支1描述
测试修改分支名称 >> 『名称』不能为空。
测试修改分支名称 >> 分支名称已存在

*/
$changeName   = array('name' => '修改后的分支1');
$changeStatus = array('status' => 'closed');
$changeDesc   = array('desc' => '修改后的分支1描述');
$emptyName    = array('name' => '');
$repeatName   = array('name' => '分支2');

$branch = new branchTest();

r($branch->updateTest($changeName))   && p('0:field,old,new') && e('name,分支1,修改后的分支1'); // 测试修改分支名称
r($branch->updateTest($changeStatus)) && p('0:field,old,new') && e('status,active,closed');     // 测试修改分支状态
r($branch->updateTest($changeDesc))   && p('0:field,old,new') && e('desc,,修改后的分支1描述');  // 测试修改分支描述
r($branch->updateTest($emptyName))    && p('name:0')          && e('『名称』不能为空。');       // 测试修改分支名称
r($branch->updateTest($repeatName))   && p('name:0')          && e('分支名称已存在');           // 测试修改分支名称
