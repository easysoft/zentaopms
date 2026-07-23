#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getIssueTreeList();
timeout=0
cid=0

- 测试file类型 >> 0
- 测试返回类型有效 >> 1
- 测试rule类型 >> 0
- 测试默认参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getissuetreelistTest(0, 0, 'file')) && p() && e('0');
$result = $test->getissuetreelistTest(0, 0, 'file');
r(is_array($result) || is_bool($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getissuetreelistTest(0, 0, 'rule')) && p() && e('0');
$result2 = $test->getissuetreelistTest(1, 0, 'file');
r(is_array($result2) || is_bool($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getissuetreelistTest(0, 1, 'rule')) && p() && e('0');
