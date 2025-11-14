#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getBuildPairs();
timeout=0
cid=15490

- 测试产品为空时，获取版本数据属性trunk @主干
- 测试产品1-5下的版本数据属性trunk @主干
- 测试分支1下的版本数据属性trunk @主干
- 测试不包含trunk的版本数据属性1 @版本1
- 测试不包含停止维护的版本数据属性1 @版本1
- 测试包含分支的版本数据属性1 @版本1
- 测试包含项目的版本数据属性1 @版本1
- 测试不包含删除的版本数据属性1 @版本1
- 测试关联执行的版本数据属性1 @版本1
- 测试不包含关联发布的版本数据属性1 @版本1
- 测试包含发布标签的版本数据属性1 @版本1
- 测试项目下的版本数据属性1 @版本1
- 测试执行下的版本数据属性1 @版本1
- 测试包含版本1-5的版本数据属性2 @版本2
- 测试将发布名称替换为版本名称的数据属性1 @版本1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(10);
zenData('project')->loadYaml('execution')->gen(30);
zenData('branch')->loadYaml('branch')->gen(5);
zenData('release')->loadYaml('release')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 5);
$branchIdList     = array('all', '1');
$paramsList       = array('noempty', 'notrunk', 'noterminate', 'withbranch', 'hasproject', 'noDeleted', 'singled', 'noreleased', 'releasedtag');
$objectIdList     = array(0, 11, 101);
$objectTypeList   = array('', 'project', 'execution');
$buildIdList      = array('', '1,2,3,4,5');
$replaceList      = array(false, true);

global $tester;
$tester->loadModel('build');
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('trunk') && e('主干');  // 测试产品为空时，获取版本数据
r($tester->build->getBuildPairs($productIdList[1], $branchIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('trunk') && e('主干');  // 测试产品1-5下的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[1], $paramsList[0], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('trunk') && e('主干');  // 测试分支1下的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[1], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试不包含trunk的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[2], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试不包含停止维护的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[3], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试包含分支的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[4], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试包含项目的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[5], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试不包含删除的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[6], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试关联执行的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[7], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试不包含关联发布的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[8], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试包含发布标签的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[0], $objectIdList[1], $objectTypeList[1], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试项目下的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[0], $objectIdList[2], $objectTypeList[2], $buildIdList[0], $replaceList[0])) && p('1')     && e('版本1'); // 测试执行下的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $buildIdList[1], $replaceList[0])) && p('2')     && e('版本2'); // 测试包含版本1-5的版本数据
r($tester->build->getBuildPairs($productIdList[0], $branchIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0], $buildIdList[0], $replaceList[1])) && p('1')     && e('版本1'); // 测试将发布名称替换为版本名称的数据
