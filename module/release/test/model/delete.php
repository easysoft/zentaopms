#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::delete();
timeout=0
cid=17985

- 步骤1：测试删除无效ID（0）的发布 @0
- 步骤2：测试删除不存在ID的发布 @0
- 步骤3：测试删除存在的发布属性deleted @1
- 步骤4：测试删除有shadow构建的发布属性deleted @1
- 步骤5：测试删除有关联构建的发布属性deleted @1
- 步骤6：测试删除另一个有shadow构建的发布属性deleted @1
- 步骤7：测试多次删除同一发布（幂等性验证）属性alreadyDeleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 准备测试数据 - 手动插入数据避免system字段问题
global $tester;
$dao = $tester->dao;

// 清理测试数据
$dao->delete()->from(TABLE_RELEASE)->where('id')->between(1, 10)->exec();
$dao->delete()->from(TABLE_BUILD)->where('id')->between(1, 10)->exec();

// 手动插入build数据
$buildData = array(
    array('id' => '1', 'project' => '11', 'product' => '1', 'name' => '构建1', 'execution' => '0', 'date' => '2023-01-01', 'deleted' => '0'),
    array('id' => '2', 'project' => '12', 'product' => '1', 'name' => '构建2', 'execution' => '0', 'date' => '2023-01-01', 'deleted' => '0'),
    array('id' => '3', 'project' => '0', 'product' => '1', 'name' => '构建3', 'execution' => '0', 'date' => '2023-01-02', 'deleted' => '0'),
    array('id' => '4', 'project' => '0', 'product' => '1', 'name' => '构建4', 'execution' => '0', 'date' => '2023-01-01', 'deleted' => '0'),
    array('id' => '5', 'project' => '0', 'product' => '1', 'name' => '构建5', 'execution' => '101', 'date' => '2023-01-03', 'deleted' => '0')
);

foreach($buildData as $build)
{
    $dao->insert(TABLE_BUILD)->data($build)->exec();
}

// 手动插入release数据
$releaseData = array(
    array('id' => '1', 'project' => '11', 'product' => '1', 'name' => '发布1', 'build' => '1', 'shadow' => '0', 'status' => 'normal', 'deleted' => '0', 'createdDate' => '2023-01-01 10:00:00'),
    array('id' => '2', 'project' => '12', 'product' => '1', 'name' => '发布2', 'build' => '2', 'shadow' => '2', 'status' => 'normal', 'deleted' => '0', 'createdDate' => '2023-01-01 10:00:00'),
    array('id' => '3', 'project' => '0', 'product' => '1', 'name' => '发布3', 'build' => '3', 'shadow' => '0', 'status' => 'normal', 'deleted' => '0', 'createdDate' => '2023-01-02 10:00:00'),
    array('id' => '4', 'project' => '0', 'product' => '1', 'name' => '发布4', 'build' => '4', 'shadow' => '4', 'status' => 'normal', 'deleted' => '0', 'createdDate' => '2023-01-01 10:00:00'),
    array('id' => '5', 'project' => '0', 'product' => '2', 'name' => '发布5', 'build' => '5', 'shadow' => '0', 'status' => 'terminate', 'deleted' => '0', 'createdDate' => '2023-01-03 10:00:00')
);

foreach($releaseData as $release)
{
    $dao->insert(TABLE_RELEASE)->data($release)->exec();
}

zenData('user')->gen(5);
su('admin');

$releaseTester = new releaseTest();

r($releaseTester->deleteTest(0)) && p() && e('0'); // 步骤1：测试删除无效ID（0）的发布
r($releaseTester->deleteTest(999)) && p() && e('0'); // 步骤2：测试删除不存在ID的发布
r($releaseTester->deleteTest(1)) && p('deleted') && e('1'); // 步骤3：测试删除存在的发布
r($releaseTester->deleteTest(2)) && p('deleted') && e('1'); // 步骤4：测试删除有shadow构建的发布
r($releaseTester->deleteTest(3)) && p('deleted') && e('1'); // 步骤5：测试删除有关联构建的发布
r($releaseTester->deleteTest(4)) && p('deleted') && e('1'); // 步骤6：测试删除另一个有shadow构建的发布
r($releaseTester->deleteTest(1)) && p('alreadyDeleted') && e('1'); // 步骤7：测试多次删除同一发布（幂等性验证）