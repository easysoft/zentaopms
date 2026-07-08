#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterSpecialChars();
timeout=0
cid=0

- 步骤1：对象字符串去除双引号实体 @Pivot
- 步骤2：对象字符串解码 HTML 实体 @Tom & Jerry
- 步骤3：数组字符串去除双引号实体 @Cell
- 步骤4：数字字段保持不变 @1
- 步骤5：空数组直接返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

$objectRecords = array((object)array('title' => '&quot;Pivot&quot;', 'html' => 'Tom &amp; Jerry', 'count' => 1));
$arrayRecords  = array(array('title' => '&quot;Cell&quot;', 'html' => 'A &lt; B'));

$objectResult = $pivotTest->filterSpecialCharsTest($objectRecords);
$arrayResult  = $pivotTest->filterSpecialCharsTest($arrayRecords);

r($objectResult[0]->title) && p() && e('Pivot');
r($objectResult[0]->html) && p() && e('Tom & Jerry');
r($arrayResult[0]['title']) && p() && e('Cell');
r($objectResult[0]->count) && p() && e('1');
r(count($pivotTest->filterSpecialCharsTest(array()))) && p() && e('0');
