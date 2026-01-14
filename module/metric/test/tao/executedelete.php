#!/usr/bin/env php
<?php

/**

title=测试 metricTao::executeDelete();
timeout=0
cid=17161

- 步骤1：删除test_code_1的记录（5个deleted=1） @5
- 步骤2：删除test_code_2的记录（2个value=0） @2
- 步骤3：删除test_code_3的记录（5个deleted=1） @5
- 步骤4：删除test_code_4的记录（无符合条件记录） @0
- 步骤5：测试空的度量编码参数 @invalid_code

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
global $tester;
$tester->dao->delete()->from(TABLE_METRICLIB)->exec();

// 手动插入测试数据
$records = array(
    array('metricCode' => 'test_code_1', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_1', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_1', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_1', 'value' => '100', 'deleted' => '1'),
    array('metricCode' => 'test_code_1', 'value' => '100', 'deleted' => '1'),
    array('metricCode' => 'test_code_2', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_2', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_2', 'value' => '200', 'deleted' => '0'),
    array('metricCode' => 'test_code_2', 'value' => '200', 'deleted' => '0'),
    array('metricCode' => 'test_code_2', 'value' => '200', 'deleted' => '0'),
    array('metricCode' => 'test_code_3', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_3', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_3', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_3', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_3', 'value' => '0', 'deleted' => '1'),
    array('metricCode' => 'test_code_4', 'value' => '300', 'deleted' => '0'),
    array('metricCode' => 'test_code_4', 'value' => '300', 'deleted' => '0'),
    array('metricCode' => 'test_code_4', 'value' => '300', 'deleted' => '0'),
    array('metricCode' => 'test_code_4', 'value' => '300', 'deleted' => '0'),
    array('metricCode' => 'test_code_4', 'value' => '300', 'deleted' => '0'),
    array('metricCode' => 'test_code_5', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_5', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_5', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_5', 'value' => '0', 'deleted' => '0'),
    array('metricCode' => 'test_code_5', 'value' => '0', 'deleted' => '0')
);

foreach($records as $index => $record) {
    $record['id'] = $index + 1;
    $record['year'] = '2024';
    $record['month'] = '01';
    $record['day'] = sprintf('%02d', $index + 1);
    $record['date'] = '2024-01-' . sprintf('%02d', $index + 1) . ' 00:00:00';
    $tester->dao->insert(TABLE_METRICLIB)->data($record)->exec();
}

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->executeDeleteTest('test_code_1')) && p() && e('5'); // 步骤1：删除test_code_1的记录（5个deleted=1）
r($metricTest->executeDeleteTest('test_code_2')) && p() && e('2'); // 步骤2：删除test_code_2的记录（2个value=0）
r($metricTest->executeDeleteTest('test_code_3')) && p() && e('5'); // 步骤3：删除test_code_3的记录（5个deleted=1）
r($metricTest->executeDeleteTest('test_code_4')) && p() && e('0'); // 步骤4：删除test_code_4的记录（无符合条件记录）
r($metricTest->executeDeleteTest('')) && p() && e('invalid_code'); // 步骤5：测试空的度量编码参数