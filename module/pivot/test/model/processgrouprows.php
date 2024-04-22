#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->initGroups().
timeout=0
cid=1

- 测试生成的表头信息是否正确。
 -  @一级项目集
 - 属性8 @单位时间交付需求规模数6
- 测试生成的数据是否正确。
 - 属性3 @3
 - 属性4 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('user')->gen(20);
zenData('bug')->loadYaml('bug')->gen(20);
zenData('product')->loadYaml('product')->gen(10);
zenData('module')->gen(10);
zenData('case')->gen(10);
zenData('project')->loadYaml('project_gensheet')->gen(20);
zenData('product')->gen(10);
zenData('task')->gen(10);

$pivotTest = new pivotTest();

$cols = array();
list($pivot, $sql, $filterFormat, $fields, $langs) = $pivotTest->getPivotSheetConfig(1001);
list($groups, $groupList, $groupCol) = $pivotTest->initGroups($fields, $pivot->settings, $langs);

$groupRows = $pivotTest->processGroupRowsTest($pivot->settings['columns'], $sql, $filterFormat, $groups, $groupList, $fields, 'show', $cols, $langs);

$check = true;

$list = get_object_vars($groupRows[0]);
r(array_keys($list)) && p('0,8') && e('一级项目集,单位时间交付需求规模数6');  //测试生成的表头信息是否正确。
r(array_values($list)) && p('3,4') && e('3,3');                               //测试生成的数据是否正确。