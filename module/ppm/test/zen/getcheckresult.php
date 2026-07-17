#!/usr/bin/env php
<?php

/**

title=测试 ppmZen::getCheckResult();
timeout=0
cid=0

- 执行ppmZen模块的getCheckResultTest方法，参数是$ppm, 'approved' 属性apiMessage @资源未找到。
- 执行ppmZen模块的getCheckResultTest方法，参数是$ppm, 'approved', array 属性apiMessage @资源未找到。
- 执行ppmZen模块的getCheckResultTest方法，参数是$ppm, 'pending' 属性apiMessage @资源未找到。
- 执行ppmZen模块的getCheckResultTest方法，参数是$ppm, 'approved', array 属性apiMessage @资源未找到。
- 执行ppmZen模块的getCheckResultTest方法，参数是$ppm, 'approved' 属性apiMessage @资源未找到。

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
$issue   = (object)array('status' => 'active', 'type' => 'codeerror');

r($ppmZen->getCheckResultTest($ppm, 'approved')) && p('apiMessage') && e('资源未找到。');
r($ppmZen->getCheckResultTest($ppm, 'approved', array(), 'pullreq')) && p('apiMessage') && e('资源未找到。');
r($ppmZen->getCheckResultTest($ppm, 'pending')) && p('apiMessage') && e('资源未找到。');
r($ppmZen->getCheckResultTest($ppm, 'approved', array($issue))) && p('apiMessage') && e('资源未找到。');
$ppm->reviewStatus = 'approved';
r($ppmZen->getCheckResultTest($ppm, 'approved')) && p('apiMessage') && e('资源未找到。');