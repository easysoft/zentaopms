#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/branch.class.php';
su('admin');

/**

title=测试 branchModel->batchUpdate();
cid=1
pid=1

测试批量更新名称 >> name,分支9,修改分支9的名称
测试批量更新描述 >> desc,,修改分支9的描述
测试批量更新状态 >> status,active,closed

*/

$changeName   = array('name' => array('9' => '修改分支9的名称', '10' => '修改后的分支10名称'));
$changeDesc   = array('desc' => array('9' => '修改分支9的描述', '10' => '修改后的分支10描述'));
$changeStatus = array('status' => array('9' => 'closed', '10' => 'closed'));

$branch = new branchTest();

r($branch->batchUpdateTest($changeName))   && p('field,old,new') && e('name,分支9,修改分支9的名称'); // 测试批量更新名称
r($branch->batchUpdateTest($changeDesc))   && p('field,old,new') && e('desc,,修改分支9的描述');      // 测试批量更新描述
r($branch->batchUpdateTest($changeStatus)) && p('field,old,new') && e('status,active,closed');       // 测试批量更新状态
