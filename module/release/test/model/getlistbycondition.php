#!/usr/bin/env php
<?php

/**

title=测试 releaseModel->getListByCondition();
timeout=0
cid=17992

- 测试根据ID列表获取发布列表信息第1条的name属性 @发布1
- 测试根据ID列表获取发布数量 @1
- 测试根据包含发布的ID列表获取发布列表信息第2条的name属性 @发布2
- 测试根据包含发布的ID列表获取发布数量 @3
- 测试根据ID列表获取发布列表信息第2条的name属性 @发布2
- 测试根据ID列表获取发布数量 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('build')->loadYaml('build')->gen(10);
zenData('user')->gen(5);
su('admin');

$release = zenData('release')->loadYaml('release');
$release->releases->range('[],[1,3]');
$release->gen(10);

global $tester;
$tester->loadModel('release');

$idList         = array(1, 100);
$includeRelease = 0;
r($tester->release->getListByCondition($idList, $includeRelease))        && p('1:name') && e('发布1');  // 测试根据ID列表获取发布列表信息
r(count($tester->release->getListByCondition($idList, $includeRelease))) && p()         && e('1');      // 测试根据ID列表获取发布数量

$idList         = array();
$includeRelease = 1;
r($tester->release->getListByCondition($idList, $includeRelease))        && p('2:name') && e('发布2');  // 测试根据包含发布的ID列表获取发布列表信息
r(count($tester->release->getListByCondition($idList, $includeRelease))) && p()         && e('3');      // 测试根据包含发布的ID列表获取发布数量

$idList = array(1, 2, 3);
r($tester->release->getListByCondition($idList))        && p('2:name') && e('发布2');  // 测试根据ID列表获取发布列表信息
r(count($tester->release->getListByCondition($idList))) && p()         && e('3');      // 测试根据ID列表获取发布数量