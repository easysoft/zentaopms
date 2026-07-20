#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::getCheckResult();
timeout=0
cid=0

- 执行$result @1
- 执行$result->message @1
- 执行$result->conflictFiles @1
- 执行$result, 'message' @1
- 执行$result2 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->rawModule = 'ppm';
$app->rawMethod = 'view';
$app->setMethodName('view');

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('user')->gen(3);

su('admin');

$ppmZen = new ppmZenTest();
$ppm    = (object)array('id' => 9101, 'repoID' => 42, 'targetRepoID' => 42, 'sourceBranch' => 'feature/demo', 'targetBranch' => 'release/main', 'reviewStatus' => 'pending');

$result = $ppmZen->getCheckResultTest($ppm, 'approved');
r(is_object($result)) && p() && e('1');
r(isset($result->message)) && p() && e('1');
r(isset($result->conflictFiles)) && p() && e('1');
r(property_exists($result, 'message')) && p() && e('1');
$result2 = $ppmZen->getCheckResultTest($ppm, 'approved');
r(is_object($result2)) && p() && e('1');