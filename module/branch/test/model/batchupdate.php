#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/branch.class.php';

zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->batchUpdate();
timeout=0
cid=1

*/

$changeName   = array('name'   => array('1' => '修改分支1的名称', '3' => '修改后的分支3名称'));
$changeDesc   = array('desc'   => array('1' => '修改分支1的描述', '3' => '修改后的分支3描述'));
$changeStatus = array('status' => array('1' => 'closed',          '3' => 'closed'));

$branch = new branchTest();

r($branch->batchUpdateTest($changeName))   && p('0:field,old,new') && e('name,分支1,修改分支1的名称'); // 测试批量更新名称
r($branch->batchUpdateTest($changeDesc))   && p('0:field,old,new') && e('desc,~~,修改分支1的描述');    // 测试批量更新描述
r($branch->batchUpdateTest($changeStatus)) && p('0:field,old,new') && e('status,active,closed');       // 测试批量更新状态
