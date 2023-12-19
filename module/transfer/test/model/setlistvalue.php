#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('project')->gen(15);
zdTable('bug')->gen(10);
zdTable('case')->gen(10);
zdTable('story')->gen(10);
su('admin');

/**

title=测试 transfer->setListValue();
timeout=0
cid=1

- 测试导出bug类型字段的下拉值第typeList条的1属性 @代码错误
- 测试导出bug项目字段的下拉数量 @5
- 测试导出bug时下拉字段的数量 @10
- 测试导出用例时story字段的下拉数量 @5
- 测试导出用例时的级联字段第cascade条的story属性 @module

*/
$transfer = new transferTest();

$result1 = $transfer->setListValueTest('bug');
$result1['typeList'] = explode(',', $result1['typeList']);

r($result1)        && p('typeList:1')     && e('代码错误');    // 测试导出bug类型字段的下拉值
r(count($result1['projectList'])) && p('') && e('5');  // 测试导出bug项目字段的下拉数量
r(count($result1['listStyle']))   && p('') && e('10'); // 测试导出bug时下拉字段的数量

$result2 = $transfer->setListValueTest('testcase');
r(count($result2['storyList']))  && p('') && e('5');      // 测试导出用例时story字段的下拉数量
r($result2)  && p('cascade:story')        && e('module'); // 测试导出用例时的级联字段
