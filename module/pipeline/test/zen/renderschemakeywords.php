#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::renderSchemaKeywords()
timeout=0
cid=0

- 返回全部4个json schema关键字链接 @4
- 返回的关键字顺序与配置一致 @repos,repoIDs,artifactLibs,scanPlans
- repos关键字链接指向pipeline模块的ajaxGetRepos方法 @1
- artifactLibs关键字链接指向artifact模块的ajaxGetArtifactLibs方法 @1
- scanPlans关键字链接指向codescan模块的ajaxGetScanPlans方法 @1
- 传入流水线对象时链接携带spaceID和repoID参数 @1
- 链接参数以传入的params为准 @1
- 空流水线且无参数时链接不携带spaceID参数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(1);

su('admin');

$tester->loadModel('pipeline');
helper::import($tester->app->getModulePath('', 'pipeline') . 'control.php');
helper::import($tester->app->getModulePath('', 'pipeline') . 'zen.php');

$pipeline = new stdclass();
$pipeline->spaceID = 5;
$pipeline->repoID  = 9;

$result = callZenMethod('pipeline', 'renderSchemaKeywords', array($pipeline, ''));
r(count($result)) && p() && e('4'); // 返回全部4个json schema关键字链接
r(implode(',', array_keys($result))) && p() && e('repos,repoIDs,artifactLibs,scanPlans'); // 返回的关键字顺序与配置一致
r(strpos($result['repos'], 'm=pipeline&f=ajaxGetRepos') !== false) && p() && e('1'); // repos关键字链接指向pipeline模块的ajaxGetRepos方法
r(strpos($result['artifactLibs'], 'm=artifact&f=ajaxGetArtifactLibs') !== false) && p() && e('1'); // artifactLibs关键字链接指向artifact模块的ajaxGetArtifactLibs方法
r(strpos($result['scanPlans'], 'm=codescan&f=ajaxGetScanPlans') !== false) && p() && e('1'); // scanPlans关键字链接指向codescan模块的ajaxGetScanPlans方法
r(strpos($result['artifactLibs'], 'spaceID=5') !== false && strpos($result['artifactLibs'], 'repoID=9') !== false) && p() && e('1'); // 传入流水线对象时链接携带spaceID和repoID参数

$paramsResult = callZenMethod('pipeline', 'renderSchemaKeywords', array($pipeline, 'spaceID=7'));
r(strpos($paramsResult['repos'], 'spaceID=7') !== false) && p() && e('1'); // 链接参数以传入的params为准

$emptyResult = callZenMethod('pipeline', 'renderSchemaKeywords', array((object)array(), ''));
r(strpos($emptyResult['repos'], 'spaceID=') === false) && p() && e('1'); // 空流水线且无参数时链接不携带spaceID参数
