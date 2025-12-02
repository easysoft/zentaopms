#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->getListBySystem();
timeout=0
cid=17993

- 测试ID存在的情况第1条的name属性 @发布1
- 测试ID为空的情况 @0
- 测试ID不存在的情况 @0
- 测试ID部分存在的情况属性100 @~~
- 测试ID部分存在的情况第2条的name属性 @发布2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('build')->loadYaml('build')->gen(10);
zenData('user')->gen(5);
su('admin');

$release = zenData('release')->loadYaml('release');
$release->system->range('1-3');
$release->gen(10);

global $tester;
$tester->loadModel('release');

$systems = array(0, 1, 100);
r($tester->release->getListBySystem(array($systems[1]))) && p('1:name') && e('发布1');  // 测试ID存在的情况
r($tester->release->getListBySystem(array($systems[0]))) && p() && e('0');              // 测试ID为空的情况
r($tester->release->getListBySystem(array($systems[2]))) && p() && e('0');              // 测试ID不存在的情况

$systems[] = 2;
r($tester->release->getListBySystem($systems)) && p('100')    && e('~~');    // 测试ID部分存在的情况
r($tester->release->getListBySystem($systems)) && p('2:name') && e('发布2'); // 测试ID部分存在的情况