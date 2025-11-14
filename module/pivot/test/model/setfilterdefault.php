#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::setFilterDefault();
timeout=0
cid=17432

- 执行$result1[0]['default'] ===  @1
- 执行$result2[0]['default'] == $monday @1
- 执行$result3[0]['default'] == $sunday @1
- 执行$result4[0]['default'] == $monthBegin @1
- 执行$result5[0]['default'] == $monthEnd @1
- 执行$result6[0]['default'] == '$MONDAY @1
- 执行$result7[0]['default'] == 'normal_string @1
- 执行$result8[0]['default']['start'] == '2023-01-01' && $result8[0]['default']['end'] == '2023-12-31 @1
- 执行$result9[0]['type'] == 'select' && $result9[0]['field'] == 'status @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivot = new pivotTest();

// 计算预期的日期值
$monday = date('Y-m-d', time() - (date('N') - 1) * 24 * 3600);
$sunday = date('Y-m-d', time() + (7 - date('N')) * 24 * 3600);
$monthBegin = date('Y-m-d', time() - (date('j') - 1) * 24 * 3600);
$monthEnd = date('Y-m-d', time() + (date('t') - date('j')) * 24 * 3600);

// 测试步骤1：空default值，processDateVar为true
$filters1 = array(array('default' => ''));
$result1 = $pivot->setFilterDefault($filters1, true);
r($result1[0]['default'] === '') && p('') && e('1');

// 测试步骤2：$MONDAY日期变量，processDateVar为true
$filters2 = array(array('default' => '$MONDAY'));
$result2 = $pivot->setFilterDefault($filters2, true);
r($result2[0]['default'] == $monday) && p('') && e('1');

// 测试步骤3：$SUNDAY日期变量，processDateVar为true
$filters3 = array(array('default' => '$SUNDAY'));
$result3 = $pivot->setFilterDefault($filters3, true);
r($result3[0]['default'] == $sunday) && p('') && e('1');

// 测试步骤4：$MONTHBEGIN日期变量，processDateVar为true
$filters4 = array(array('default' => '$MONTHBEGIN'));
$result4 = $pivot->setFilterDefault($filters4, true);
r($result4[0]['default'] == $monthBegin) && p('') && e('1');

// 测试步骤5：$MONTHEND日期变量，processDateVar为true
$filters5 = array(array('default' => '$MONTHEND'));
$result5 = $pivot->setFilterDefault($filters5, true);
r($result5[0]['default'] == $monthEnd) && p('') && e('1');

// 测试步骤6：日期变量，processDateVar为false
$filters6 = array(array('default' => '$MONDAY'));
$result6 = $pivot->setFilterDefault($filters6, false);
r($result6[0]['default'] == '$MONDAY') && p('') && e('1');

// 测试步骤7：非日期字符串，processDateVar为true
$filters7 = array(array('default' => 'normal_string'));
$result7 = $pivot->setFilterDefault($filters7, true);
r($result7[0]['default'] == 'normal_string') && p('') && e('1');

// 测试步骤8：数组类型default值
$filters8 = array(array('default' => array('start' => '2023-01-01', 'end' => '2023-12-31')));
$result8 = $pivot->setFilterDefault($filters8, true);
r($result8[0]['default']['start'] == '2023-01-01' && $result8[0]['default']['end'] == '2023-12-31') && p('') && e('1');

// 测试步骤9：不存在default字段的过滤器
$filters9 = array(array('type' => 'select', 'field' => 'status'));
$result9 = $pivot->setFilterDefault($filters9, true);
r($result9[0]['type'] == 'select' && $result9[0]['field'] == 'status') && p('') && e('1');