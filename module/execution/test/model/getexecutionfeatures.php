#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->getExecutionFeatures();
timeout=0
cid=16313

- 获取运维类型迭代禁用的功能
 - 属性story @0
 - 属性task @1
 - 属性qa @0
 - 属性devops @1
 - 属性burn @0
 - 属性build @0
 - 属性other @1
 - 属性plan @1
- 获取需求阶段禁用的功能
 - 属性story @1
 - 属性task @1
 - 属性qa @0
 - 属性devops @0
 - 属性burn @1
 - 属性build @0
 - 属性other @0
 - 属性plan @0
- 获取设计阶段禁用的功能
 - 属性story @1
 - 属性task @1
 - 属性qa @0
 - 属性devops @0
 - 属性burn @1
 - 属性build @0
 - 属性other @0
 - 属性plan @1
- 获取评审阶段禁用的功能
 - 属性story @0
 - 属性task @1
 - 属性qa @0
 - 属性devops @0
 - 属性burn @1
 - 属性build @0
 - 属性other @0
 - 属性plan @0
- 获取项目型项目的执行禁用的功能
 - 属性story @1
 - 属性task @1
 - 属性qa @1
 - 属性devops @1
 - 属性burn @1
 - 属性build @1
 - 属性other @1
 - 属性plan @0

*/

$opsExecution = new stdclass();
$opsExecution->lifetime = 'ops';

$requestStage = new stdclass();
$requestStage->lifetime  = '';
$requestStage->attribute = 'request';

$designStage = new stdclass();
$designStage->lifetime  = '';
$designStage->attribute = 'design';

$reviewStage = new stdclass();
$reviewStage->lifetime  = '';
$reviewStage->attribute = 'review';

$projectExecution = new stdclass();
$projectExecution->lifetime  = '';
$projectExecution->projectInfo = new stdclass();
$projectExecution->projectInfo->model      = 'waterfall';
$projectExecution->projectInfo->hasProduct = 0;

$executionTester = new executionTest();
r($executionTester->getExecutionFeaturesTest($opsExecution))     && p('story,task,qa,devops,burn,build,other,plan') && e('0,1,0,1,0,0,1,1'); // 获取运维类型迭代禁用的功能
r($executionTester->getExecutionFeaturesTest($requestStage))     && p('story,task,qa,devops,burn,build,other,plan') && e('1,1,0,0,1,0,0,0'); // 获取需求阶段禁用的功能
r($executionTester->getExecutionFeaturesTest($designStage))      && p('story,task,qa,devops,burn,build,other,plan') && e('1,1,0,0,1,0,0,1'); // 获取设计阶段禁用的功能
r($executionTester->getExecutionFeaturesTest($reviewStage))      && p('story,task,qa,devops,burn,build,other,plan') && e('0,1,0,0,1,0,0,0'); // 获取评审阶段禁用的功能
r($executionTester->getExecutionFeaturesTest($projectExecution)) && p('story,task,qa,devops,burn,build,other,plan') && e('1,1,1,1,1,1,1,0'); // 获取项目型项目的执行禁用的功能
