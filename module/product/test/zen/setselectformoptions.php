#!/usr/bin/env php
<?php

/**

title=测试 productZen::setSelectFormOptions();
timeout=0
cid=17614

- 步骤1:用户选项已展开 @1
- 步骤2:用户组选项已设置 @1
- 步骤3:项目集选项已设置 @1
- 步骤4:产品线选项已设置 @1
- 步骤5:字段属性已设置 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(10);
zenData('group')->gen(5);
zenData('project')->loadYaml('program', false, 2)->gen(5);
zenData('module')->gen(0);

su('admin');

$productTest = new productZenTest();

/* 测试步骤1:测试带有options='users'字段的情况 */
$fields1 = array('PO' => array('options' => 'users'), 'name' => array());
$result1 = $productTest->setSelectFormOptionsTest(0, $fields1);
r(!empty($result1['PO']['options']) && is_array($result1['PO']['options'])) && p('') && e('1'); // 步骤1:用户选项已展开

/* 测试步骤2:测试带有groups字段的情况 */
$fields2 = array('groups' => array('options' => array()), 'name' => array());
$result2 = $productTest->setSelectFormOptionsTest(0, $fields2);
r(isset($result2['groups']['options']) && is_array($result2['groups']['options'])) && p('') && e('1'); // 步骤2:用户组选项已设置

/* 测试步骤3:测试带有program字段的情况 */
$fields3 = array('program' => array('options' => array()), 'name' => array());
$result3 = $productTest->setSelectFormOptionsTest(0, $fields3);
r(isset($result3['program']['options']) && is_array($result3['program']['options'])) && p('') && e('1'); // 步骤3:项目集选项已设置

/* 测试步骤4:测试带有line字段的情况,programID为0 */
$fields4 = array('line' => array('options' => array()), 'name' => array());
$result4 = $productTest->setSelectFormOptionsTest(0, $fields4);
r(isset($result4['line']['options']) && is_array($result4['line']['options'])) && p('') && e('1'); // 步骤4:产品线选项已设置

/* 测试步骤5:测试字段name和title属性设置 */
$fields5 = array('testField' => array('options' => 'users'), 'anotherField' => array());
$result5 = $productTest->setSelectFormOptionsTest(0, $fields5);
r(isset($result5['testField']['name']) && isset($result5['testField']['title']) && isset($result5['anotherField']['name']) && isset($result5['anotherField']['title'])) && p('') && e('1'); // 步骤5:字段属性已设置