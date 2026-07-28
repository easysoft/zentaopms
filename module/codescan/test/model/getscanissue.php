#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->getScanIssue();
timeout=0
cid=0

- 测试showBug为true >> 0
- 测试问题2展示bug返回0 >> 2,1,0,none
- 测试showBug为false >> 0
- 测试默认参数 >> 0
- 测试问题3隐藏bug返回0 >> 3,0,0,none

*/

$test = new codescanModelTest();

r($test->getscanissueTest(1, true)) && p() && e('0');
r($test->getscanissueTest(2, true)) && p() && e('0');
r($test->getscanissueTest(0, false)) && p() && e('0');
r($test->getscanissueTest(3, false)) && p() && e('0');
r($test->getscanissueTest(4, true)) && p() && e('0');
