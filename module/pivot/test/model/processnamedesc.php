#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processNameDesc();
timeout=0
cid=17422

- 执行pivot模块的processNameDescTest方法，参数是$pivotWithType 属性name @original_name
- 执行$result2->names @1
- 执行pivot模块的processNameDescTest方法，参数是$validPivot 属性name @中文名称
- 执行pivot模块的processNameDescTest方法，参数是$validPivot2 属性desc @中文描述
- 执行name) && empty($result5模块的desc方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

// 测试步骤1：有type字段的pivot对象，应该直接返回不处理
$pivotWithType = new stdClass();
$pivotWithType->type = 'builtin';
$pivotWithType->name = 'original_name';
$pivotWithType->desc = 'original_desc';
r($pivot->processNameDescTest($pivotWithType)) && p('name') && e('original_name');

// 测试步骤2：空name和desc的pivot对象处理，验证names数组初始化
$emptyPivot = new stdClass();
$result2 = $pivot->processNameDescTest($emptyPivot);
r(isset($result2->names)) && p() && e('1');

// 测试步骤3：有效JSON格式的name处理，客户端语言为zh-cn
$validPivot = new stdClass();
$validPivot->name = '{"zh-cn":"中文名称","en":"English Name"}';
r($pivot->processNameDescTest($validPivot)) && p('name') && e('中文名称');

// 测试步骤4：有效JSON格式的desc处理
$validPivot2 = new stdClass();
$validPivot2->desc = '{"zh-cn":"中文描述","en":"English Desc"}';
r($pivot->processNameDescTest($validPivot2)) && p('desc') && e('中文描述');

// 测试步骤5：无效JSON格式的name和desc处理，应该保持为空
$invalidPivot = new stdClass();
$invalidPivot->name = 'invalid json';
$invalidPivot->desc = 'invalid json desc';
$result5 = $pivot->processNameDescTest($invalidPivot);
r(empty($result5->name) && empty($result5->desc)) && p() && e('1');