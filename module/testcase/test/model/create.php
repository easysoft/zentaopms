#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel->create();
timeout=0
cid=18969

- 测试创建用例1属性status @1
- 测试创建用例2属性status @2
- 测试创建用例3属性status @3
- 测试创建没有填写名字的用例 @『用例名称』不能为空。
- 测试创建没有填写类型的用例 @『用例类型』不能为空。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('0');

su('admin');

$testcase1 = array('title' => '测试创建测试用例1');
$testcase2 = array('title' => '测试创建测试用例2', 'pri' => 1, 'type' => 'performance');
$testcase3 = array('title' => '测试创建测试用例3', 'keywords' => '测试关键词3', 'stage' => 'unittest,smoke');

$no_title = array('title' => '');
$no_type  = array('type' => '', 'title' => '测试创建没有填写类型的用例');

$testcase = new testcaseTest();

r($testcase->createTest($testcase1)) && p('status') && e('1');                      // 测试创建用例1
r($testcase->createTest($testcase2)) && p('status') && e('2');                      // 测试创建用例2
r($testcase->createTest($testcase3)) && p('status') && e('3');                      // 测试创建用例3
r($testcase->createTest($no_title))  && p()         && e('『用例名称』不能为空。'); // 测试创建没有填写名字的用例
r($testcase->createTest($no_type))   && p()         && e('『用例类型』不能为空。'); // 测试创建没有填写类型的用例