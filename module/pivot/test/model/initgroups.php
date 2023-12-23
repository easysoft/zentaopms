#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->processDataVar();
timeout=0
cid=1

- 测试生成的分组信息是否正确。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivotTest = new pivotTest();

$pivot = $tester->loadModel('pivot')->getById(1001);
$fields = json_decode(json_encode($pivot->fieldSettings), true);
$langs  = json_decode($pivot->langs, true) ?? array();

$result = $pivotTest->initGroups($fields, $pivot->settings, $langs);

$condition1 = isset($result[0]) && $result[0][0] === '一级项目集' && $result[0][1] === '项目名称';
$condition2 = isset($result[1]) && $result[1] == 'tt.`一级项目集`,tt.`项目名称`';
$condition3 = isset($result[2]) && $result[2][0]->name == '一级项目集' && $result[2][0]->isGroup = true;
$condition4 = isset($result[2]) && $result[2][1]->name == '项目名称' && $result[2][1]->isGroup = true;

r($condition1 && $condition2 && $condition3 && $condition4) && p('') && e(1);   //测试生成的分组信息是否正确。