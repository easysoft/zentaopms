#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->batchUpdate();
timeout=0
cid=15319

- 测试批量更新名称
 - 第0条的field属性 @name
 - 第0条的old属性 @分支1
 - 第0条的new属性 @修改分支1的名称
- 测试批量更新描述
 - 第0条的field属性 @desc
 - 第0条的old属性 @~~
 - 第0条的new属性 @修改分支1的描述
- 测试批量更新状态
 - 第0条的field属性 @status
 - 第0条的old属性 @active
 - 第0条的new属性 @closed
- 测试激活分支3
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @active
- 测试分支名称已存在属性name[2] @分支名称已存在

*/

$changeName1   = array('name'    => array('1' => '修改分支1的名称'));
$changeDesc1   = array('desc'    => array('1' => '修改分支1的描述'));
$changeStatus1 = array('status'  => array('1' => 'closed'));
$existName     = array('name'    => array('2' => '修改分支1的名称'));
$changeStatus3 = array('status'  => array('3' => 'active'));

$branch = new branchTest();

r($branch->batchUpdateTest($changeName1)[1])    && p('0:field,old,new') && e('name,分支1,修改分支1的名称'); // 测试批量更新名称
r($branch->batchUpdateTest($changeDesc1)[1])    && p('0:field,old,new') && e('desc,~~,修改分支1的描述');    // 测试批量更新描述
r($branch->batchUpdateTest($changeStatus1)[1])  && p('0:field,old,new') && e('status,active,closed');       // 测试批量更新状态
r($branch->batchUpdateTest($changeStatus3)[3])  && p('0:field,old,new') && e('status,closed,active');       // 测试激活分支3
r($branch->batchUpdateTest($existName))         && p('name[2]')         && e('分支名称已存在');             // 测试分支名称已存在
