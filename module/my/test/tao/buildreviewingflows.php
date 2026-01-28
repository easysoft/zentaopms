#!/usr/bin/env php
<?php

/**

title=测试 myTao::buildReviewingFlows();
timeout=0
cid=17306

- 步骤1：空数据测试 @0
- 步骤2：第一个对象的ID第0条的id属性 @1
- 步骤3：多对象类型数量 @3
- 步骤4：自定义标题字段第0条的title属性 @Test Charter
- 步骤5：使用流程名称第0条的title属性 @Custom Flow #1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$myTest = new myTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：空数据测试
r(count($myTest->buildReviewingFlowsTest(array(), array(), array()))) && p() && e('0'); // 步骤1：空数据测试

// 步骤2：单个对象类型的正常数据
$objectGroup = array(
    'story' => array(
        (object)array('id' => 1, 'title' => 'Test Story 1', 'openedDate' => '2023-01-01 10:00:00', 'product' => 1, 'project' => 1)
    )
);
$flows = array();
$objectNameFields = array('story' => 'title');
r($myTest->buildReviewingFlowsTest($objectGroup, $flows, $objectNameFields)) && p('0:id') && e('1'); // 步骤2：第一个对象的ID

// 步骤3：多个对象类型的数据处理
$objectGroup = array(
    'story' => array(
        (object)array('id' => 1, 'title' => 'Test Story 1', 'openedDate' => '2023-01-01 10:00:00', 'product' => 1),
        (object)array('id' => 2, 'title' => 'Test Story 2', 'openedDate' => '2023-01-02 11:00:00', 'product' => 2)
    ),
    'bug' => array(
        (object)array('id' => 3, 'title' => 'Test Bug 1', 'openedDate' => '2023-01-03 12:00:00', 'product' => 1)
    )
);
$objectNameFields = array('story' => 'title', 'bug' => 'title');
r(count($myTest->buildReviewingFlowsTest($objectGroup, $flows, $objectNameFields))) && p() && e('3'); // 步骤3：多对象类型数量

// 步骤4：包含自定义标题字段的流程
$objectGroup = array(
    'charter' => array(
        (object)array('id' => 1, 'name' => 'Test Charter', 'createdDate' => '2023-01-01 10:00:00', 'reviewStatus' => 'reviewing', 'project' => 1)
    )
);
$flows = array(
    'charter' => (object)array('titleField' => 'name', 'app' => 'project')
);
$objectNameFields = array();
r($myTest->buildReviewingFlowsTest($objectGroup, $flows, $objectNameFields)) && p('0:title') && e('Test Charter'); // 步骤4：自定义标题字段

// 步骤5：缺少标题字段时使用流程名称
$objectGroup = array(
    'custom' => array(
        (object)array('id' => 1, 'createdDate' => '2023-01-01 10:00:00', 'project' => 1)
    )
);
$flows = array(
    'custom' => (object)array('name' => 'Custom Flow', 'app' => 'custom')
);
$objectNameFields = array();
r($myTest->buildReviewingFlowsTest($objectGroup, $flows, $objectNameFields)) && p('0:title') && e('Custom Flow #1'); // 步骤5：使用流程名称