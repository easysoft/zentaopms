#!/usr/bin/env php
<?php

/**

title=测试 buildModel->selectedBuildPairs();
timeout=0
cid=15513

- 测试不传buildIdList获取给定ID列表的版本数据 @0
- 测试传入buildIdList获取给定ID列表的版本数据属性1 @版本1
- 测试传入buildIdList获取给定ID列表且属于产品1-5的版本数据属性2 @版本2
- 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除的版本数据属性3 @版本3
- 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除且属于敏捷项目ID11的版本数据属性1 @版本1
- 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除且属于执行ID101的版本数据属性1 @版本1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(5);
zenData('user')->gen(5);
su('admin');

$productIdList[0]  = array();
$productIdList[1]  = range(1, 5);

$paramsList     = array('', 'hasdeleted');
$objectTypeList = array('', 'project', 'execution');
$objectIdList   = array(0, 11, 101);
$buildIdList    = array('', '1,2,3,4,5');

global $tester;
$tester->loadModel('build');
r($tester->build->selectedBuildPairs($buildIdList[0], $productIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0])) && p()    && e('0');     // 测试不传buildIdList获取给定ID列表的版本数据
r($tester->build->selectedBuildPairs($buildIdList[1], $productIdList[0], $paramsList[0], $objectIdList[0], $objectTypeList[0])) && p('1') && e('版本1'); // 测试传入buildIdList获取给定ID列表的版本数据
r($tester->build->selectedBuildPairs($buildIdList[1], $productIdList[1], $paramsList[0], $objectIdList[0], $objectTypeList[0])) && p('2') && e('版本2'); // 测试传入buildIdList获取给定ID列表且属于产品1-5的版本数据
r($tester->build->selectedBuildPairs($buildIdList[1], $productIdList[1], $paramsList[1], $objectIdList[0], $objectTypeList[0])) && p('3') && e('版本3'); // 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除的版本数据
r($tester->build->selectedBuildPairs($buildIdList[1], $productIdList[1], $paramsList[1], $objectIdList[1], $objectTypeList[1])) && p('1') && e('版本1'); // 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除且属于敏捷项目ID11的版本数据
r($tester->build->selectedBuildPairs($buildIdList[1], $productIdList[1], $paramsList[1], $objectIdList[2], $objectTypeList[2])) && p('1') && e('版本1'); // 测试传入buildIdList获取给定ID列表且属于产品1-5且未删除且属于执行ID101的版本数据