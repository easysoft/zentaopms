#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->editPlan();
timeout=0
cid=0

- step1 >> 0
- step2 >> 1
- step3 >> 0
- step4 >> 1
- step5 >> 0

*/

$test = new codescanModelTest();
$formData = new stdclass();
$formData->solutionIDs = array(1);

r($test->editplanTest(1, 1, $formData)) && p() && e('0');
$result = $test->editplanTest(2, 2, $formData);
r(is_array($result) || is_bool($result) ? '1' : '0') && p() && e('1');
r($test->editplanTest(3, 3, $formData)) && p() && e('0');
$result2 = $test->editplanTest(4, 4, new stdclass());
r(is_array($result2) || is_bool($result2) ? '1' : '0') && p() && e('1');
