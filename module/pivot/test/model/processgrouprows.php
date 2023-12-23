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
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('user')->gen(20);
zdTable('bug')->config('bug')->gen(20);
zdTable('product')->config('product')->gen(10);
zdTable('module')->gen(10);
zdTable('case')->gen(10);
zdTable('project')->config('project_gensheet')->gen(20);
zdTable('product')->gen(10);
zdTable('task')->gen(10);

$pivotTest = new pivotTest();

$cols = array();
list($pivot, $sql, $filterFormat, $fields, $langs) = $pivotTest->getPivotSheetConfig(1001);
list($groups, $groupList, $groupCol) = $pivotTest->initGroups($fields, $pivot->settings, $langs);

$groupRows = $pivotTest->processGroupRowsTest($pivot->settings['columns'], $sql, $filterFormat, $groups, $groupList, $fields, 'show', $cols, $langs);

$check = true;

$list = get_object_vars($groupRows[0]);
r(array_keys($list)) && p('0,8') && e('一级项目集,单位时间交付需求规模数6');  //测试生成的表头信息是否正确。
r(array_values($list)) && p('3,4') && e('3,3');                               //测试生成的数据是否正确。