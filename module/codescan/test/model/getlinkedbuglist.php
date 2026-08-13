#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

/**

title=测试 codescanModel->getLinkedBugList();
timeout=0
cid=0

- 测试默认空参数返回数组 >> 0
- 测试空问题列表返回0 >> empty,0,empty,0
- 测试带status参数 >> 0
- 测试带int参数 >> 0
- 测试单个问题active状态返回0 >> 1,1,active,0

*/

$test = new codescanModelTest();

r($test->getlinkedbuglistTest()) && p() && e('0');
r($test->getlinkedbuglistTest(array())) && p() && e('0');
r($test->getlinkedbuglistTest(array(1, 2, 3))) && p() && e('0');
r($test->getlinkedbuglistTest(1, 'active')) && p() && e('0');
r($test->getlinkedbuglistTest(0, 'resolved')) && p() && e('0');
