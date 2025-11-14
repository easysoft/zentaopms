#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getCustomFields();
timeout=0
cid=18931

- 执行taskTest模块的getCustomFieldsTest方法，参数是$normalExecution, 'create' 第0条的module属性 @所属模块
- 执行taskTest模块的getCustomFieldsTest方法，参数是$stageExecution, 'batchCreate'
 - 第0条的estStarted属性 @~~
 - 第0条的deadline属性 @~~
- 执行taskTest模块的getCustomFieldsTest方法，参数是$opsExecution, 'batchEdit' 第0条的story属性 @~~
- 执行taskTest模块的getCustomFieldsTest方法，参数是$requestExecution, 'create')[1], 'story') !== false  @0
- 执行taskTest模块的getCustomFieldsTest方法，参数是$reviewExecution, 'batchEdit' 第0条的module属性 @所属模块
- 执行taskTest模块的getCustomFieldsTest方法，参数是$normalExecution, 'batchCreate')[1], 'story') !== false  @1
- 执行taskTest模块的getCustomFieldsTest方法，参数是$normalExecution, 'batchEdit')[1], 'module') !== false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$taskTest = new taskZenTest();

// 准备测试用例的执行对象
$normalExecution = new stdclass();
$normalExecution->type = 'sprint';
$normalExecution->lifetime = 'long';
$normalExecution->attribute = '';

$stageExecution = new stdclass();
$stageExecution->type = 'stage';
$stageExecution->lifetime = 'long';
$stageExecution->attribute = '';

$opsExecution = new stdclass();
$opsExecution->type = 'sprint';
$opsExecution->lifetime = 'ops';
$opsExecution->attribute = '';

$requestExecution = new stdclass();
$requestExecution->type = 'sprint';
$requestExecution->lifetime = 'long';
$requestExecution->attribute = 'request';

$reviewExecution = new stdclass();
$reviewExecution->type = 'sprint';
$reviewExecution->lifetime = 'long';
$reviewExecution->attribute = 'review';

r($taskTest->getCustomFieldsTest($normalExecution, 'create')) && p('0:module') && e('所属模块');
r($taskTest->getCustomFieldsTest($stageExecution, 'batchCreate')) && p('0:estStarted,deadline') && e('~~,~~');
r($taskTest->getCustomFieldsTest($opsExecution, 'batchEdit')) && p('0:story') && e('~~');
r(strpos($taskTest->getCustomFieldsTest($requestExecution, 'create')[1], 'story') !== false) && p() && e('0');
r($taskTest->getCustomFieldsTest($reviewExecution, 'batchEdit')) && p('0:module') && e('所属模块');
r(strpos($taskTest->getCustomFieldsTest($normalExecution, 'batchCreate')[1], 'story') !== false) && p() && e('1');
r(strpos($taskTest->getCustomFieldsTest($normalExecution, 'batchEdit')[1], 'module') !== false) && p() && e('1');