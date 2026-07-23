#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanIssueList();
timeout=0
cid=0

- step1 >> 0
- step2 >> 1
- step3 >> 0
- step4 >> 1
- step5 >> 0

*/

$test = new codescanModelTest();

r($test->getscanissuelistTest(1, array())) && p() && e('0');
$result = $test->getscanissuelistTest(2, array('page' => 1));
r(is_array($result) || is_object($result) ? '1' : '0') && p() && e('1');
r($test->getscanissuelistTest(3, array('limit' => 10))) && p() && e('0');
$result2 = $test->getscanissuelistTest(4, array('page' => 1, 'limit' => 5));
r(is_array($result2) || is_object($result2) ? '1' : '0') && p() && e('1');
