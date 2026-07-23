#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getListByAPI();
timeout=0
cid=0

- 测试带api参数 >> 0
- 测试返回类型有效 >> 1
- 测试默认参数 >> 0
- 测试带page参数 >> 0
- 测试返回类型验证 >> 1

*/

$test = new codescanModelTest();

r($test->getlistbyapiTest('gitfox', array())) && p() && e('0');
$result = $test->getlistbyapiTest('scan/list', array('page' => 1));
r(is_array($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getlistbyapiTest()) && p() && e('0');
$result2 = $test->getlistbyapiTest('scan/tasks', array('limit' => 10));
r(is_array($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
r($test->getlistbyapiTest('scan/issues', array())) && p() && e('0');
