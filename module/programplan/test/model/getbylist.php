#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getByList();
timeout=0
cid=0

- 步骤1：传入有效ID数组 @3
- 步骤2：传入空数组 @0
- 步骤3：传入不存在的ID数组 @0
- 步骤4：传入单个ID数组 @1
- 步骤5：传入混合ID数组 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 清理现有数据
global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->where('type')->eq('project')->exec();
$tester->dao->delete()->from(TABLE_PROJECTPRODUCT)->exec();

// 直接插入测试数据
$projects = array(
    array('id' => 1, 'type' => 'project', 'name' => '测试项目1', 'code' => 'PRJ001', 'status' => 'doing', 'deleted' => '0', 'openedBy' => 'admin', 'openedDate' => '2024-01-01 10:00:00'),
    array('id' => 2, 'type' => 'project', 'name' => '测试项目2', 'code' => 'PRJ002', 'status' => 'wait', 'deleted' => '0', 'openedBy' => 'admin', 'openedDate' => '2024-01-02 10:00:00'),
    array('id' => 3, 'type' => 'project', 'name' => '测试项目3', 'code' => 'PRJ003', 'status' => 'doing', 'deleted' => '0', 'openedBy' => 'admin', 'openedDate' => '2024-01-03 10:00:00'),
    array('id' => 4, 'type' => 'project', 'name' => '项目A', 'code' => 'PROJ_A', 'status' => 'closed', 'deleted' => '0', 'openedBy' => 'admin', 'openedDate' => '2024-01-04 10:00:00'),
    array('id' => 5, 'type' => 'project', 'name' => '项目B', 'code' => 'PROJ_B', 'status' => 'doing', 'deleted' => '0', 'openedBy' => 'admin', 'openedDate' => '2024-01-05 10:00:00'),
);

foreach($projects as $project) {
    $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
}

// 插入项目产品关联数据
$projectProducts = array(
    array('project' => 1, 'product' => 1, 'branch' => 0),
    array('project' => 2, 'product' => 1, 'branch' => 0),
    array('project' => 3, 'product' => 2, 'branch' => 0),
    array('project' => 4, 'product' => 2, 'branch' => 0),
    array('project' => 5, 'product' => 3, 'branch' => 0),
);

foreach($projectProducts as $pp) {
    $tester->dao->insert(TABLE_PROJECTPRODUCT)->data($pp)->exec();
}

// 用户登录
su('admin');

// 创建测试实例
$programplanTest = new programplanTest();

r($programplanTest->getByListTest(array(1, 2, 3))) && p() && e(3); // 步骤1：传入有效ID数组
r($programplanTest->getByListTest(array())) && p() && e(0); // 步骤2：传入空数组
r($programplanTest->getByListTest(array(999, 1000))) && p() && e(0); // 步骤3：传入不存在的ID数组
r($programplanTest->getByListTest(array(1))) && p() && e(1); // 步骤4：传入单个ID数组
r($programplanTest->getByListTest(array(1, 999, 2))) && p() && e(2); // 步骤5：传入混合ID数组