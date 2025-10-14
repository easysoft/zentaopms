#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
cid=0

- 步骤1：测试story模块返回字段数量 >> 期望返回6个字段
- 步骤2：测试bug模块返回字段数量 >> 期望返回13个字段
- 步骤3：测试task模块返回字段数量 >> 期望返回5个字段
- 步骤4：测试不存在模块返回空数组 >> 期望返回0个字段
- 步骤5：测试空字符串模块返回空数组 >> 期望返回0个字段

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r(count($convertTest->getZentaoFieldsTest('story'))) && p() && e('6');
r(count($convertTest->getZentaoFieldsTest('bug'))) && p() && e('13');
r(count($convertTest->getZentaoFieldsTest('task'))) && p() && e('5');
r(count($convertTest->getZentaoFieldsTest('unknown'))) && p() && e('0');
r(count($convertTest->getZentaoFieldsTest(''))) && p() && e('0');