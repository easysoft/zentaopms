#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';
zenData('project')->gen(15);
zenData('bug')->gen(10);
zenData('case')->gen(10);
zenData('story')->gen(10);
su('admin');

/**

title=测试 transfer->getCascadeList();
timeout=0
cid=19332

- 测试导出bug类型字段的下拉值第typeList条的codeerror属性 @代码错误(#codeerror)
- 测试导出bug项目字段的下拉数量 @5
- 测试导出bug时下拉字段的数量 @10
- 测试导出用例时story字段的下拉数量 @6
- 测试导出用例时的级联字段第cascade条的story属性 @module

*/
$transfer = new transferTest();

$result1 = $transfer->getCascadeListTest('bug');

r($result1) && p('typeList:codeerror') && e('代码错误(#codeerror)'); // 测试导出bug类型字段的下拉值

r(count($result1['projectList'])) && p('') && e('5');  // 测试导出bug项目字段的下拉数量
r(count($result1['listStyle']))   && p('') && e('10'); // 测试导出bug时下拉字段的数量

$result2 = $transfer->setListValueTest('testcase');
r(count($result2['storyList'])) && p('')              && e('6');      // 测试导出用例时story字段的下拉数量
r($result2)                     && p('cascade:story') && e('module'); // 测试导出用例时的级联字段
