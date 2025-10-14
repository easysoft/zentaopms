#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProgramPriv();
timeout=0
cid=0

- 执行searchTest模块的checkProgramPrivTest方法，参数是$results, $objectIdList, '1, 2, 3'  @2
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results, $objectIdList, '1, 2, 3'  @0
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results, $objectIdList, '1, 2, 3'  @1
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results, $objectIdList, '1, 2, 3'  @3
- 执行searchTest模块的checkProgramPrivTest方法，参数是$results, $objectIdList, '1, 2, 3'  @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. 不使用zendata，直接模拟测试数据

// 3. 用户登录（使用普通用户模拟有限权限）
su('user');

// 4. 创建测试实例
$searchTest = new searchTest();

// 准备测试数据
$results = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1', 'objectType' => 'program', 'objectID' => 1),
    2 => (object)array('id' => 2, 'title' => '测试结果2', 'objectType' => 'program', 'objectID' => 2),
    3 => (object)array('id' => 3, 'title' => '测试结果3', 'objectType' => 'program', 'objectID' => 4)
);

// 测试步骤1：有权限的项目集，应该保留搜索结果
$objectIdList = array(1 => 1, 2 => 2, 4 => 3);  // programID => recordID
r(count($searchTest->checkProgramPrivTest($results, $objectIdList, '1,2,3'))) && p() && e(2);

// 测试步骤2：无权限的项目集，应该移除搜索结果
$results = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1', 'objectType' => 'program', 'objectID' => 4),
    2 => (object)array('id' => 2, 'title' => '测试结果2', 'objectType' => 'program', 'objectID' => 5)
);
$objectIdList = array(4 => 1, 5 => 2);
r(count($searchTest->checkProgramPrivTest($results, $objectIdList, '1,2,3'))) && p() && e(0);

// 测试步骤3：部分有权限的项目集，应该保留有权限的结果
$results = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1', 'objectType' => 'program', 'objectID' => 1),
    2 => (object)array('id' => 2, 'title' => '测试结果2', 'objectType' => 'program', 'objectID' => 4)
);
$objectIdList = array(1 => 1, 4 => 2);
r(count($searchTest->checkProgramPrivTest($results, $objectIdList, '1,2,3'))) && p() && e(1);

// 测试步骤4：空的对象ID列表，应该返回原始结果
$results = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1', 'objectType' => 'program', 'objectID' => 1),
    2 => (object)array('id' => 2, 'title' => '测试结果2', 'objectType' => 'program', 'objectID' => 2),
    3 => (object)array('id' => 3, 'title' => '测试结果3', 'objectType' => 'program', 'objectID' => 3)
);
$objectIdList = array();
r(count($searchTest->checkProgramPrivTest($results, $objectIdList, '1,2,3'))) && p() && e(3);

// 测试步骤5：不存在的项目集ID，应该移除对应结果
$results = array(
    1 => (object)array('id' => 1, 'title' => '测试结果1', 'objectType' => 'program', 'objectID' => 1),
    2 => (object)array('id' => 2, 'title' => '测试结果2', 'objectType' => 'program', 'objectID' => 999)
);
$objectIdList = array(1 => 1, 999 => 2);
r(count($searchTest->checkProgramPrivTest($results, $objectIdList, '1,2,3'))) && p() && e(1);