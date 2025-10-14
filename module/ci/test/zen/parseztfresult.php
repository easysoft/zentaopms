#!/usr/bin/env php
<?php

/**

title=测试 ciZen::parseZtfResult();
timeout=0
cid=0

- 执行ciTest模块的parseZtfResultTest方法，参数是$unitPost, 1, 1, 1, 1  @1
- 执行ciTest模块的parseZtfResultTest方法，参数是$funcPost, 2, 1, 1, 1  @1
- 执行ciTest模块的parseZtfResultTest方法，参数是$emptyUnitPost, 3, 1, 1, 1  @1
- 执行ciTest模块的parseZtfResultTest方法，参数是$emptyFuncPost, 4, 1, 1, 1  @1
- 执行ciTest模块的parseZtfResultTest方法，参数是$boundaryPost, 0, 0, 0, 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

su('admin');

$ciTest = new ciTest();

// 步骤1：测试正常单元测试类型解析
$unitPost = new stdClass();
$unitPost->testType = 'unit';
$unitPost->testFrame = 'junit';
$unitPost->unitResult = array();

r($ciTest->parseZtfResultTest($unitPost, 1, 1, 1, 1)) && p() && e('1');

// 步骤2：测试正常功能测试类型解析
$funcPost = new stdClass();
$funcPost->testType = 'func';
$funcPost->testFrame = 'junit';
$funcPost->funcResult = array();

r($ciTest->parseZtfResultTest($funcPost, 2, 1, 1, 1)) && p() && e('1');

// 步骤3：测试空测试结果解析（单元测试）
$emptyUnitPost = new stdClass();
$emptyUnitPost->testType = 'unit';
$emptyUnitPost->testFrame = 'junit';
$emptyUnitPost->unitResult = array();

r($ciTest->parseZtfResultTest($emptyUnitPost, 3, 1, 1, 1)) && p() && e('1');

// 步骤4：测试空测试结果解析（功能测试）
$emptyFuncPost = new stdClass();
$emptyFuncPost->testType = 'func';
$emptyFuncPost->testFrame = 'junit';
$emptyFuncPost->funcResult = array();

r($ciTest->parseZtfResultTest($emptyFuncPost, 4, 1, 1, 1)) && p() && e('1');

// 步骤5：测试边界参数值
$boundaryPost = new stdClass();
$boundaryPost->testType = 'unit';
$boundaryPost->testFrame = 'phpunit';
$boundaryPost->unitResult = array();

r($ciTest->parseZtfResultTest($boundaryPost, 0, 0, 0, 0)) && p() && e('1');