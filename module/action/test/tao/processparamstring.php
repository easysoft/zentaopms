#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processParamString();
timeout=0
cid=14966

- 执行actionTest模块的processParamStringTest方法，参数是$buildAction, 'build'  @buildID=123&type=story
- 执行actionTest模块的processParamStringTest方法，参数是$bugAction, 'bug'  @bugID=456&type=bug
- 执行actionTest模块的processParamStringTest方法，参数是$testtaskAction, 'testtask'  @taskID=789
- 执行actionTest模块的processParamStringTest方法，参数是$executionAction, 'execution'  @executionID=101
- 执行actionTest模块的processParamStringTest方法，参数是$projectAction, 'project'  @projectID=102&productID=1,2

- 执行actionTest模块的processParamStringTest方法，参数是$productplanAction, 'productplan'  @planID=201
- 执行actionTest模块的processParamStringTest方法，参数是$storyAction, 'story'  @storyID=301
- 执行actionTest模块的processParamStringTest方法，参数是$releaseAction, 'release'  @releaseID=401&type=release
- 执行actionTest模块的processParamStringTest方法，参数是$unknownAction, 'unknown'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$actionTest = new actionTaoTest();

// 创建测试用的 action 对象
$buildAction = new stdClass();
$buildAction->extra = '123';
$buildAction->objectType = 'story';

$bugAction = new stdClass();
$bugAction->extra = '456';
$bugAction->objectType = 'bug';

$testtaskAction = new stdClass();
$testtaskAction->extra = '789';

$executionAction = new stdClass();
$executionAction->extra = '101';

$projectAction = new stdClass();
$projectAction->execution = '102';
$projectAction->product = '1,2,';

$productplanAction = new stdClass();
$productplanAction->extra = '201';

$storyAction = new stdClass();
$storyAction->extra = '301';

$releaseAction = new stdClass();
$releaseAction->extra = '401';
$releaseAction->objectType = 'release';

$unknownAction = new stdClass();
$unknownAction->extra = '999';

r($actionTest->processParamStringTest($buildAction, 'build')) && p() && e('buildID=123&type=story');
r($actionTest->processParamStringTest($bugAction, 'bug')) && p() && e('bugID=456&type=bug');
r($actionTest->processParamStringTest($testtaskAction, 'testtask')) && p() && e('taskID=789');
r($actionTest->processParamStringTest($executionAction, 'execution')) && p() && e('executionID=101');
r($actionTest->processParamStringTest($projectAction, 'project')) && p() && e('projectID=102&productID=1,2');
r($actionTest->processParamStringTest($productplanAction, 'productplan')) && p() && e('planID=201');
r($actionTest->processParamStringTest($storyAction, 'story')) && p() && e('storyID=301');
r($actionTest->processParamStringTest($releaseAction, 'release')) && p() && e('releaseID=401&type=release');
r($actionTest->processParamStringTest($unknownAction, 'unknown')) && p() && e('0');