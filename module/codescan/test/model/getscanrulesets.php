#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRulesets();
timeout=0
cid=0

- 测试空数组参数 >> 0
- 测试返回类型有效 >> 1
- 测试带ID的数组 >> 0
- 测试带page的数组 >> 1
- 测试带limit的数组 >> 0

*/

$test = new codescanModelTest();

r($test->getscanrulesetsTest(array())) && p() && e('0');
$result = $test->getscanrulesetsTest(array('id' => 1));
r(is_array($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getscanrulesetsTest(array('page' => 1))) && p() && e('0');
$result2 = $test->getscanrulesetsTest(array('limit' => 10));
r(is_array($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getscanrulesetsTest(array('sort' => 'id'))) && p() && e('0');
