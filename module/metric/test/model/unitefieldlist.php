#!/usr/bin/env php
<?php

/**

title=测试 metricModel::uniteFieldList();
timeout=0
cid=17158

- 步骤1：空数组情况 @0
- 步骤2：简单字段 @`id`,`name`,`status`

- 步骤3：字段去重 @`id`,`name`,`status`,`type`

- 步骤4：表前缀转换 @`user`.`id` AS `user_id`,`user`.`name` AS `user_name`,`project`.`type` AS `project_type`

- 步骤5：保持AS格式 @COUNT(*) AS total_count,SUM(value) AS total_value

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 创建模拟计算器对象
class MockCalc1 {
    public $fieldList = array();
}

class MockCalc2 {
    public $fieldList = array();
}

// 步骤1：空数组输入测试
r($metricTest->uniteFieldListTest(array())) && p() && e('0'); // 步骤1：空数组情况

// 步骤2：单个计算器对象包含简单字段测试
$calc1 = new MockCalc1();
$calc1->fieldList = array('id', 'name', 'status');
r($metricTest->uniteFieldListTest(array($calc1))) && p() && e('`id`,`name`,`status`'); // 步骤2：简单字段

// 步骤3：多个计算器对象包含相同字段测试（去重）
$calc2 = new MockCalc2();
$calc2->fieldList = array('name', 'type', 'id');
r($metricTest->uniteFieldListTest(array($calc1, $calc2))) && p() && e('`id`,`name`,`status`,`type`'); // 步骤3：字段去重

// 步骤4：包含带表前缀字段的计算器对象测试
$calc3 = new MockCalc1();
$calc3->fieldList = array('user.id', 'user.name', 'project.type');
r($metricTest->uniteFieldListTest(array($calc3))) && p() && e('`user`.`id` AS `user_id`,`user`.`name` AS `user_name`,`project`.`type` AS `project_type`'); // 步骤4：表前缀转换

// 步骤5：包含已有AS别名字段的计算器对象测试
$calc4 = new MockCalc2();
$calc4->fieldList = array('COUNT(*) AS total_count', 'SUM(value) AS total_value');
r($metricTest->uniteFieldListTest(array($calc4))) && p() && e('COUNT(*) AS total_count,SUM(value) AS total_value'); // 步骤5：保持AS格式