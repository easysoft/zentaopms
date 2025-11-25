#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printPreAndNext();
timeout=0
cid=15701

- 测试步骤1:在onlybody模式下调用printPreAndNext返回false @0
- 测试步骤2:传入空的preAndNext对象只输出nav标签 @<nav class='container'></nav>
- 测试步骤3:传入包含pre对象生成上一个导航按钮 @1
- 测试步骤4:传入包含next对象生成下一个导航按钮 @1
- 测试步骤5:同时传入pre和next对象生成完整的导航按钮 @1
- 测试步骤6:传入自定义链接模板生成导航链接 @1
- 测试步骤7:传入文档类型对象生成特殊导航按钮 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

// 准备测试数据
$emptyPreAndNext = new stdClass();

$preOnlyData = new stdClass();
$preOnlyData->pre = new stdClass();
$preOnlyData->pre->id = 1;
$preOnlyData->pre->title = '上一个任务';

$nextOnlyData = new stdClass();
$nextOnlyData->next = new stdClass();
$nextOnlyData->next->id = 3;
$nextOnlyData->next->title = '下一个任务';

$fullData = new stdClass();
$fullData->pre = new stdClass();
$fullData->pre->id = 1;
$fullData->pre->title = '上一个任务';
$fullData->next = new stdClass();
$fullData->next->id = 3;
$fullData->next->title = '下一个任务';

$customTemplateData = new stdClass();
$customTemplateData->pre = new stdClass();
$customTemplateData->pre->id = 5;
$customTemplateData->pre->title = '自定义任务';

$docData = new stdClass();
$docData->pre = new stdClass();
$docData->pre->id = 10;
$docData->pre->title = '文档';
$docData->pre->objectType = 'doc';

r($commonTest->printPreAndNextTest('', '', true)) && p() && e('0');  // 测试步骤1:在onlybody模式下调用printPreAndNext返回false
r($commonTest->printPreAndNextTest($emptyPreAndNext, '')) && p() && e("<nav class='container'></nav>");  // 测试步骤2:传入空的preAndNext对象只输出nav标签
r(strpos($commonTest->printPreAndNextTest($preOnlyData, ''), 'prevPage') !== false && strpos($commonTest->printPreAndNextTest($preOnlyData, ''), 'icon-chevron-left') !== false) && p() && e('1');  // 测试步骤3:传入包含pre对象生成上一个导航按钮
r(strpos($commonTest->printPreAndNextTest($nextOnlyData, ''), 'nextPage') !== false && strpos($commonTest->printPreAndNextTest($nextOnlyData, ''), 'icon-chevron-right') !== false) && p() && e('1');  // 测试步骤4:传入包含next对象生成下一个导航按钮
r(strpos($commonTest->printPreAndNextTest($fullData, ''), 'prevPage') !== false && strpos($commonTest->printPreAndNextTest($fullData, ''), 'nextPage') !== false) && p() && e('1');  // 测试步骤5:同时传入pre和next对象生成完整的导航按钮
r(strpos($commonTest->printPreAndNextTest($customTemplateData, '/custom/link/%s'), '/custom/link/5') !== false) && p() && e('1');  // 测试步骤6:传入自定义链接模板生成导航链接
r(strpos($commonTest->printPreAndNextTest($docData, ''), 'javascript:void(0)') !== false) && p() && e('1');  // 测试步骤7:传入文档类型对象生成特殊导航按钮