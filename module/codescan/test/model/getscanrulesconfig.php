#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRulesConfig();
timeout=0
cid=0

- 测试langs类型 >> 0
- 测试返回类型有效 >> 1
- 测试tags类型 >> 0
- 测试默认空值 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getscanrulesconfigTest('langs')) && p() && e('0');
$result = $test->getscanrulesconfigTest('tags');
r(is_array($result) ? '1' : '0') && p() && e('1');
r($test->getscanrulesconfigTest('plugins')) && p() && e('0');
$result2 = $test->getscanrulesconfigTest('types');
r(is_array($result2) ? '1' : '0') && p() && e('1');
r($test->getscanrulesconfigTest('')) && p() && e('0');
