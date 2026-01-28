#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::generateTableCols();
timeout=0
cid=17369

- 执行$result1) && count($result1) > 0 @1
- 执行$result2) && count($result2) == 0 @1
- 执行label) && $result3[0][0]模块的label == '自定义状态标签方法  @1
- 执行isGroup) && $result4[0][0]模块的isGroup === true方法  @1
- 执行$result5) && isset($result5[0]) && count($result5[0]) == 3 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
$pivotTest = new pivotModelTest();

// 测试步骤1：正常输入情况
$fields1 = array(
    'product.name' => array('object' => 'product', 'field' => 'name'),
    'story.status' => array('object' => 'story', 'field' => 'status')
);
$groups1 = array('product.name', 'story.status');
$langs1 = array(
    'product.name' => array('zh-cn' => '产品名称', 'en' => 'Product Name'),
    'story.status' => array('zh-cn' => '需求状态', 'en' => 'Story Status')
);
$result1 = $pivotTest->generateTableColsTest($fields1, $groups1, $langs1);
r(is_array($result1) && count($result1) > 0) && p() && e('1');

// 测试步骤2：边界值输入，空分组数组
$fields2 = array(
    'task.name' => array('object' => 'task', 'field' => 'name')
);
$groups2 = array();
$langs2 = array();
$result2 = $pivotTest->generateTableColsTest($fields2, $groups2, $langs2);
r(is_array($result2) && count($result2) == 0) && p() && e('1');

// 测试步骤3：测试多语言标签处理
$fields3 = array(
    'bug.status' => array('object' => 'bug', 'field' => 'status')
);
$groups3 = array('bug.status');
$langs3 = array(
    'bug.status' => array('zh-cn' => '自定义状态标签', 'en' => 'Custom Status Label')
);
$result3 = $pivotTest->generateTableColsTest($fields3, $groups3, $langs3);
r(isset($result3[0][0]->label) && $result3[0][0]->label == '自定义状态标签') && p() && e('1');

// 测试步骤4：测试字段和分组属性
$fields4 = array(
    'user.role' => array('object' => 'user', 'field' => 'role')
);
$groups4 = array('user.role');
$langs4 = array();
$result4 = $pivotTest->generateTableColsTest($fields4, $groups4, $langs4);
r(isset($result4[0][0]->isGroup) && $result4[0][0]->isGroup === true) && p() && e('1');

// 测试步骤5：测试复杂字段对象和多个分组情况
$fields5 = array(
    'project.name' => array('object' => 'project', 'field' => 'name'),
    'execution.status' => array('object' => 'execution', 'field' => 'status'),
    'task.pri' => array('object' => 'task', 'field' => 'pri')
);
$groups5 = array('project.name', 'execution.status', 'task.pri');
$langs5 = array(
    'project.name' => array('zh-cn' => '项目名称'),
    'execution.status' => array('zh-cn' => '执行状态')
);
$result5 = $pivotTest->generateTableColsTest($fields5, $groups5, $langs5);
r(is_array($result5) && isset($result5[0]) && count($result5[0]) == 3) && p() && e('1');