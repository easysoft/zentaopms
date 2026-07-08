#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processFieldSettings();
timeout=0
cid=0

- 步骤1：fieldSettings 为空时不处理 @0
- 步骤2：合法透视表重建 fieldSettings @4
- 步骤3：非法 SQL 不改动 fieldSettings @1
- 步骤4：不自动补充不存在的 project 字段 @0
- 步骤5：保留并更新 BSA 字段配置 @project:BSA

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester, $app;
$tester->dbh->exec(file_get_contents($app->getAppRoot() . 'test/data/pivot.sql'));

su('admin');

$pivotTest = new pivotModelTest();

$emptyPivot                = $pivotTest->getByIDTest(1023);
$emptyPivot->fieldSettings = array();
$emptyPivot->fields        = '';

$validPivot  = $pivotTest->getByIDTest(1023);
$invalidPivot = $pivotTest->getByIDTest(1023);
$invalidPivot->sql = 'xxx';
$invalidBefore = serialize($invalidPivot->fieldSettings);

$bsaPivot = $pivotTest->getByIDTest(1003);

$emptyResult   = $pivotTest->processFieldSettingsTest($emptyPivot);
$validResult   = $pivotTest->processFieldSettingsTest($validPivot);
$invalidResult = $pivotTest->processFieldSettingsTest($invalidPivot);
$bsaResult     = $pivotTest->processFieldSettingsTest($bsaPivot);

r(count($emptyResult->fieldSettings)) && p() && e('0');
r(count(get_object_vars($validResult->fieldSettings))) && p() && e('4');
r($invalidBefore === serialize($invalidResult->fieldSettings) ? '1' : '0') && p() && e('1');
r(isset($bsaResult->fieldSettings->project)) && p() && e('0');
r($bsaResult->fieldSettings->BSA->object . ':' . $bsaResult->fieldSettings->BSA->field) && p() && e('project:BSA');
