#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->gen(0);
su('admin');

/**

title=测试 branchModel->create();
timeout=0
cid=15323

*/
$productID = 6;

$branch1 = array('name' => '新建分支1', 'desc' => '新建分支1的描述');
$branch2 = array('name' => '新建分支2', 'desc' => '新建分支2的描述');

$repeatName1 = array('name' => '新建分支1', 'desc' => '重名新建分支1的描述');
$repeatName2 = array('name' => '分支1', 'desc' => '重名分支1的描述');
$emptyName   = array('name' => '', 'desc' => '分支名为空的分支描述');

$branch = new branchModelTest();

r($branch->createTest($productID, $branch1))     && p('name,desc') && e('新建分支1,新建分支1的描述'); // 测试新建分支1
r($branch->createTest($productID, $branch2))     && p('name,desc') && e('新建分支2,新建分支2的描述'); // 测试新建分支2
r($branch->createTest($productID, $repeatName1)) && p('name:0')    && e('分支名称已存在');            // 测试新建 重名新建分支1
r($branch->createTest($productID, $repeatName2)) && p('name')      && e('分支1');                     // 测试新建 分支1
r($branch->createTest($productID, $emptyName))   && p('name:0')    && e('『名称』不能为空。');        // 测试新建 名称为空的分支
