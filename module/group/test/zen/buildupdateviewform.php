#!/usr/bin/env php
<?php

/**

title=测试 groupZen::buildUpdateViewForm();
timeout=0
cid=16732

- 步骤1：正常情况下的表单数据构造 @1
- 步骤2：包含空值的表单数据处理 @1
- 步骤3：只包含部分字段的表单数据 @1
- 步骤4：actionallchecker复选框选中状态 @1
- 步骤5：空表单数据处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

su('admin');

$groupZenTest = new groupZenTest();

$result1 = $groupZenTest->buildUpdateViewFormTest(array(
    'views' => array('all', 'lite', ''),
    'programs' => array('1', '2'),
    'projects' => array('3', '4', ''),
    'products' => array('5'),
    'sprints' => array('6', '7', '8'),
    'actions' => array('action1', 'action2')
));
r(isset($result1['views'][0]) && $result1['views'][0] == 'all' ? 1 : 0) && p() && e('1'); // 步骤1：正常情况下的表单数据构造

$result2 = $groupZenTest->buildUpdateViewFormTest(array(
    'views' => array('', '', ''),
    'programs' => array('1', '', '3'),
    'projects' => array(''),
    'products' => array(),
    'sprints' => array('sprint1'),
    'actions' => array('')
));
r(isset($result2['programs'][0]) && $result2['programs'][0] == '1' ? 1 : 0) && p() && e('1'); // 步骤2：包含空值的表单数据处理

$result3 = $groupZenTest->buildUpdateViewFormTest(array(
    'views' => array('view1', 'view2'),
    'programs' => array()
));
r(isset($result3['views'][0]) && $result3['views'][0] == 'view1' ? 1 : 0) && p() && e('1'); // 步骤3：只包含部分字段的表单数据

$result4 = $groupZenTest->buildUpdateViewFormTest(array(
    'views' => array('view1'),
    'programs' => array('prog1'),
    'projects' => array('proj1'),
    'products' => array('prod1'),
    'sprints' => array('sprint1'),
    'actions' => array('act1')
), true);
r($result4['actionallchecker'] === true ? 1 : 0) && p() && e('1'); // 步骤4：actionallchecker复选框选中状态

$result5 = $groupZenTest->buildUpdateViewFormTest();
r($result5['actionallchecker'] === false ? 1 : 0) && p() && e('1'); // 步骤5：空表单数据处理