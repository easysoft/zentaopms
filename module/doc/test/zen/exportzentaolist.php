#!/usr/bin/env php
<?php

/**

title=测试 docZen::exportZentaoList();
timeout=0
cid=16188

- 应该返回2个元素：heading和table @2
- 空数据应该没有data属性 @0
- 空列配置应该没有cols属性 @0
- 用户字段应该显示用户名 @admin
- 状态应该被正确映射 @激活

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->loadYaml('zt_user_exportzentaolist', false, 2);
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况下导出完整数据
$blockData1 = new stdClass();
$blockData1->title = '用户列表测试';
$blockData1->content = new stdClass();
$blockData1->content->cols = array(
    (object)array('name' => 'id', 'title' => 'ID', 'width' => 50, 'show' => true),
    (object)array('name' => 'account', 'title' => '用户名', 'width' => 100, 'show' => true),
    (object)array('name' => 'realname', 'title' => '真实姓名', 'width' => 120, 'show' => true)
);
$blockData1->content->data = array(
    (object)array('id' => 1, 'account' => 'admin', 'realname' => '管理员'),
    (object)array('id' => 2, 'account' => 'user1', 'realname' => '用户一')
);
$result1 = json_decode($docTest->exportZentaoListTest($blockData1), true);
r(count($result1)) && p() && e('2'); // 应该返回2个元素：heading和table

// 测试步骤2：数据为空数组的边界情况
$blockData2 = new stdClass();
$blockData2->title = '空数据测试';
$blockData2->content = new stdClass();
$blockData2->content->cols = array(
    (object)array('name' => 'id', 'title' => 'ID', 'width' => 50, 'show' => true)
);
$blockData2->content->data = array();
$result2 = json_decode($docTest->exportZentaoListTest($blockData2), true);
r(isset($result2[1]['props']['data'])) && p() && e('0'); // 空数据应该没有data属性

// 测试步骤3：列配置为空数组的边界情况
$blockData3 = new stdClass();
$blockData3->title = '空列配置测试';
$blockData3->content = new stdClass();
$blockData3->content->cols = array();
$blockData3->content->data = array(
    (object)array('id' => 1, 'account' => 'admin')
);
$result3 = json_decode($docTest->exportZentaoListTest($blockData3), true);
r(isset($result3[1]['props']['cols'])) && p() && e('0'); // 空列配置应该没有cols属性

// 测试步骤4：包含用户类型列的数据处理
$blockData4 = new stdClass();
$blockData4->title = '用户类型列测试';
$blockData4->content = new stdClass();
$blockData4->content->cols = array(
    (object)array('name' => 'assignedTo', 'title' => '指派给', 'width' => 100, 'type' => 'user', 'show' => true)
);
$blockData4->content->data = array(
    (object)array('assignedTo' => 'admin'),
    (object)array('assignedTo' => 'user1')
);
$result4 = json_decode($docTest->exportZentaoListTest($blockData4), true);
r($result4[1]['props']['data'][0]['assignedTo']['text']) && p() && e('admin'); // 用户字段应该显示用户名

// 测试步骤5：包含状态和描述类型列的数据处理
$blockData5 = new stdClass();
$blockData5->title = '状态描述列测试';
$blockData5->content = new stdClass();
$blockData5->content->cols = array(
    (object)array('name' => 'status', 'title' => '状态', 'type' => 'status', 'statusMap' => array('active' => '激活', 'inactive' => '未激活'), 'show' => true),
    (object)array('name' => 'type', 'title' => '类型', 'type' => 'desc', 'map' => array('dev' => '开发', 'qa' => '测试'), 'show' => true)
);
$blockData5->content->data = array(
    (object)array('status' => 'active', 'type' => 'dev'),
    (object)array('status' => 'inactive', 'type' => 'qa')
);
$result5 = json_decode($docTest->exportZentaoListTest($blockData5), true);
r($result5[1]['props']['data'][0]['status']['text']) && p() && e('激活'); // 状态应该被正确映射
