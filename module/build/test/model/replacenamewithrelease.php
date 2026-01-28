#!/usr/bin/env php
<?php

/**

title=测试 buildModel->replaceNameWithRelease();
timeout=0
cid=15506

- 替换版本的名称第2030-01-01条的0属性 @发布10
- 替换包含主干版本的名称第2030-01-01条的0属性 @发布10
- 替换非停止维护版本的名称第2030-01-01条的0属性 @发布10
- 替换包含分支版本的名称第2030-01-01条的0属性 @主干/发布10
- 替换包含发布标签版本的名称第2030-01-01条的0属性 @发布10 [发布]
- 替换包含没有关联发布版本的名称第2030-01-01条的0属性 @发布10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->loadYaml('build')->gen(10);
zenData('project')->loadYaml('execution')->gen(30);
zenData('branch')->loadYaml('branch')->gen(5);
zenData('release')->loadYaml('release')->gen(10);
zenData('user')->gen(5);
su('admin');

$branchIdList = array('all', '0', '1');
$paramsList   = array('', 'separate', 'noterminate', 'withbranch', 'releasetag', 'noreleased');

$buildTester = new buildModelTest();
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[0])) && p('2030-01-01:0') && e('发布10');        // 替换版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[1])) && p('2030-01-01:0') && e('发布10');        // 替换包含主干版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[2])) && p('2030-01-01:0') && e('发布10');        // 替换非停止维护版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[3])) && p('2030-01-01:0') && e('主干/发布10');   // 替换包含分支版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[4])) && p('2030-01-01:0') && e('发布10 [发布]'); // 替换包含发布标签版本的名称
r($buildTester->replaceNameWithReleaseTest($branchIdList[0], $paramsList[5])) && p('2030-01-01:0') && e('发布10');        // 替换包含没有关联发布版本的名称