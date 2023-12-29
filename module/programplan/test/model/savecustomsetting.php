#!/usr/bin/env php
<?php
/**

title=测试 programplanModel->saveCustomSetting();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$settings = new stdclass();
$settings->zooming     = '1';
$settings->stageCustom = '2';
$settings->ganttFields = '3';

global $tester;
$programplan = $tester->loadModel('programplan');
$programplan->loadModel('setting');

$programplan->saveCustomSetting($settings, 'admin', 'project');

$stageCustom = $programplan->setting->getItem('owner=admin&module=project&section=browse&key=stageCustom');
$ganttFields = $programplan->setting->getItem('owner=admin&module=project&section=ganttCustom&key=ganttFields');
$zooming     = $programplan->setting->getItem('owner=admin&module=project&section=ganttCustom&key=zooming');

r($stageCustom) && p() && e('2'); // 比较 stageCustom 的值
r($ganttFields) && p() && e('3'); // 比较 ganttFields 的值
r($zooming)     && p() && e('1'); // 比较 zooming 的值
