#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
timeout=0
cid=15788

- 测试story模块的字段数量 @6
- 测试bug模块的字段数量 @13
- 测试task模块的字段数量 @5
- 测试testcase模块的字段数量 @9
- 测试epic模块的字段数量 @6
- 测试requirement模块的字段数量 @6
- 测试不存在配置的模块返回空数组 @0
- 测试空字符串模块返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';
su('admin');

$convertTest = new convertTest();

r(count($convertTest->getZentaoFieldsTest('story'))) && p() && e('6'); // 测试story模块的字段数量
r(count($convertTest->getZentaoFieldsTest('bug'))) && p() && e('13'); // 测试bug模块的字段数量
r(count($convertTest->getZentaoFieldsTest('task'))) && p() && e('5'); // 测试task模块的字段数量
r(count($convertTest->getZentaoFieldsTest('testcase'))) && p() && e('9'); // 测试testcase模块的字段数量
r(count($convertTest->getZentaoFieldsTest('epic'))) && p() && e('6'); // 测试epic模块的字段数量
r(count($convertTest->getZentaoFieldsTest('requirement'))) && p() && e('6'); // 测试requirement模块的字段数量
r(count($convertTest->getZentaoFieldsTest('notexist'))) && p() && e('0'); // 测试不存在配置的模块返回空数组
r(count($convertTest->getZentaoFieldsTest(''))) && p() && e('0'); // 测试空字符串模块返回空数组