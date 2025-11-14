#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->saveCustomSetting();
cid=17756

- 比较 stageCustom 的值 @2
- 比较 ganttFields 的值 @3
- 比较 zooming 的值 @1
- 测试获取错误配置 zoomings @0
- 测试重复获取 ganttField 的值 @3

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
$emptySet    = $programplan->setting->getItem('owner=admin&module=project&section=ganttCustom&key=zoomings');
$ganttField  = $programplan->setting->getItem('owner=admin&module=project&section=ganttCustom&key=ganttFields');

r($stageCustom) && p() && e('2'); // 比较 stageCustom 的值
r($ganttFields) && p() && e('3'); // 比较 ganttFields 的值
r($zooming)     && p() && e('1'); // 比较 zooming 的值
r($emptySet)    && p() && e('0'); // 测试获取错误配置 zoomings
r($ganttField)  && p() && e('3'); // 测试重复获取 ganttField 的值
