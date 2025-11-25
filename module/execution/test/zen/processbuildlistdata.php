#!/usr/bin/env php
<?php

/**

title=测试 executionZen::processBuildListData();
timeout=0
cid=16435

- 执行executionZenTest模块的processBuildListDataTest方法，参数是$normalBuildList, 1 
 - 第0条的name属性 @Build 1.0
 - 第0条的pathType属性 @filePath
- 执行executionZenTest模块的processBuildListDataTest方法，参数是$branchBuildList, 2 
 - 第0条的name属性 @Build 2.0
 - 第0条的pathType属性 @scmPath
- 执行executionZenTest模块的processBuildListDataTest方法，参数是$emptyBuildList, 3  @0
- 执行executionZenTest模块的processBuildListDataTest方法，参数是$dualPathBuildList, 4  @2
- 执行executionZenTest模块的processBuildListDataTest方法，参数是$emptySystemBuildList, 5 第0条的system属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('branch')->loadYaml('branch', false, 2)->gen(20);
zenData('build')->loadYaml('build', false, 2)->gen(15);
zenData('project')->loadYaml('project', false, 2)->gen(10);

su('admin');

$executionZenTest = new executionZenTest();

// 准备测试数据 - 正常版本列表
$normalBuildList = array();
$build1 = new stdClass();
$build1->id = 1;
$build1->name = 'Build 1.0';
$build1->product = 1;
$build1->branch = '1,2';
$build1->scmPath = '';
$build1->filePath = '/path/to/file';
$build1->system = '1';
$normalBuildList[] = $build1;

// 测试数据 - 包含分支的版本
$branchBuildList = array();
$build2 = new stdClass();
$build2->id = 2;
$build2->name = 'Build 2.0';
$build2->product = 2;
$build2->branch = '3,4';
$build2->scmPath = '/scm/path';
$build2->filePath = '';
$build2->system = '2';
$branchBuildList[] = $build2;

// 测试数据 - 空列表
$emptyBuildList = array();

// 测试数据 - 包含SCM和文件路径的版本
$dualPathBuildList = array();
$build3 = new stdClass();
$build3->id = 3;
$build3->name = 'Build 3.0';
$build3->product = 3;
$build3->branch = '5';
$build3->scmPath = '/scm/path';
$build3->filePath = '/file/path';
$build3->system = '';
$dualPathBuildList[] = $build3;

// 测试数据 - 系统为空的版本
$emptySystemBuildList = array();
$build4 = new stdClass();
$build4->id = 4;
$build4->name = 'Build 4.0';
$build4->product = 1;
$build4->branch = '';
$build4->scmPath = '';
$build4->filePath = '/path/to/file';
$build4->system = '';
$emptySystemBuildList[] = $build4;

r($executionZenTest->processBuildListDataTest($normalBuildList, 1)) && p('0:name,pathType') && e('Build 1.0,filePath');
r($executionZenTest->processBuildListDataTest($branchBuildList, 2)) && p('0:name,pathType') && e('Build 2.0,scmPath');
r($executionZenTest->processBuildListDataTest($emptyBuildList, 3)) && p() && e('0');
r(count($executionZenTest->processBuildListDataTest($dualPathBuildList, 4))) && p() && e('2');
r($executionZenTest->processBuildListDataTest($emptySystemBuildList, 5)) && p('0:system') && e('~~');