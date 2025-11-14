#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getByList();
timeout=0
cid=17740

- 步骤1：传入有效ID数组 @3
- 步骤2：传入空数组 @0
- 步骤3：传入不存在的ID数组 @0
- 步骤4：传入单个ID数组 @1
- 步骤5：传入混合ID数组（存在和不存在的ID混合） @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

su('admin');

global $tester;
$dao = $tester->dao;

// 清理现有数据
$dao->delete()->from(TABLE_PROJECT)->where('id')->in('1,2,3,999,1000')->exec();

// 插入项目测试数据（只插入必要字段）
try {
    $dao->insert(TABLE_PROJECT)->data(array(
        'id' => 1,
        'type' => 'project',
        'name' => '测试项目1',
        'status' => 'doing',
        'openedBy' => 'admin',
        'openedDate' => date('Y-m-d H:i:s'),
        'deleted' => '0'
    ))->exec();

    $dao->insert(TABLE_PROJECT)->data(array(
        'id' => 2,
        'type' => 'project',
        'name' => '测试项目2',
        'status' => 'doing',
        'openedBy' => 'admin',
        'openedDate' => date('Y-m-d H:i:s'),
        'deleted' => '0'
    ))->exec();

    $dao->insert(TABLE_PROJECT)->data(array(
        'id' => 3,
        'type' => 'project',
        'name' => '测试项目3',
        'status' => 'wait',
        'openedBy' => 'admin',
        'openedDate' => date('Y-m-d H:i:s'),
        'deleted' => '0'
    ))->exec();
} catch(Exception $e) {
    // 如果插入失败，继续执行测试（测试环境可能已有数据）
}

// 创建测试实例
$programplanTest = new programplanTest();

r($programplanTest->getByListTest(array(1, 2, 3))) && p() && e(3); // 步骤1：传入有效ID数组
r($programplanTest->getByListTest(array())) && p() && e(0); // 步骤2：传入空数组
r($programplanTest->getByListTest(array(999, 1000))) && p() && e(0); // 步骤3：传入不存在的ID数组
r($programplanTest->getByListTest(array(1))) && p() && e(1); // 步骤4：传入单个ID数组
r($programplanTest->getByListTest(array(1, 999, 2))) && p() && e(2); // 步骤5：传入混合ID数组（存在和不存在的ID混合）