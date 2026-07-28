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
- 测试scan/list分页参数返回0 >> scan/list,page,1,0
- 测试默认参数 >> 0
- 测试带page参数 >> 0
- 测试scan/tasks限制参数返回0 >> scan/tasks,limit,1,0

*/

$test = new codescanModelTest();

r($test->getlistbyapiTest('gitfox', array())) && p() && e('0');
r($test->getlistbyapiTest('scan/list', array('page' => 1))) && p() && e('0');
r($test->getlistbyapiTest()) && p() && e('0');
r($test->getlistbyapiTest('scan/tasks', array('limit' => 10))) && p() && e('0');
r($test->getlistbyapiTest('scan/issues', array())) && p() && e('0');
