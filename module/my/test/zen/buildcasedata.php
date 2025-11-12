#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildCaseData();
timeout=0
cid=0

- 测试空用例列表返回结果 @0
- 测试assigntome类型处理正常用例数据第0条的title属性 @正常用例1
- 测试openedbyme类型处理正常用例数据第0条的title属性 @正常用例1
- 测试用例needconfirm标记处理第0条的status属性 @normal
- 测试用例lastRunResult为空时的默认值第0条的lastRunResult属性 @未执行
- 测试用例lastRunResult为fail时增加失败计数第0条的lastRunResult属性 @fail
- 测试用例fromCaseVersion大于version时状态变更第0条的status属性 @原用例更新

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('case')->loadYaml('buildcasedata/case', false, 2)->gen(20);
zenData('story')->loadYaml('buildcasedata/story', false, 2)->gen(10);
zenData('product')->loadYaml('buildcasedata/product', false, 2)->gen(5);
zenData('user')->gen(10);

su('admin');

$myTest = new myZenTest();

$emptyCases = array();

$case1 = new stdClass();
$case1->id = 1;
$case1->title = '正常用例1';
$case1->product = 1;
$case1->status = 'normal';
$case1->lastRunResult = 'pass';
$case1->version = 1;
$case1->fromCaseVersion = 1;
$case1->needconfirm = 0;
$case1->story = 0;
$case1->storyVersion = 1;
$case1->case = 1;

$case2 = new stdClass();
$case2->id = 2;
$case2->title = '正常用例2';
$case2->product = 1;
$case2->status = 'normal';
$case2->lastRunResult = '';
$case2->version = 1;
$case2->fromCaseVersion = 1;
$case2->needconfirm = 0;
$case2->story = 0;
$case2->storyVersion = 1;
$case2->case = 2;

$case3 = new stdClass();
$case3->id = 3;
$case3->title = '需求变更用例';
$case3->product = 1;
$case3->status = 'normal';
$case3->lastRunResult = 'pass';
$case3->version = 1;
$case3->fromCaseVersion = 1;
$case3->needconfirm = 1;
$case3->story = 1;
$case3->storyVersion = 1;
$case3->case = 3;

$case4 = new stdClass();
$case4->id = 4;
$case4->title = '执行失败用例';
$case4->product = 1;
$case4->status = 'normal';
$case4->lastRunResult = 'fail';
$case4->version = 1;
$case4->fromCaseVersion = 1;
$case4->needconfirm = 0;
$case4->story = 0;
$case4->storyVersion = 1;
$case4->case = 4;

$case5 = new stdClass();
$case5->id = 5;
$case5->title = '版本变更用例';
$case5->product = 1;
$case5->status = 'normal';
$case5->lastRunResult = 'pass';
$case5->version = 1;
$case5->fromCaseVersion = 2;
$case5->needconfirm = 0;
$case5->story = 0;
$case5->storyVersion = 1;
$case5->case = 5;

$normalCases = array($case1);
$caseWithEmptyResult = array($case2);
$caseNeedConfirm = array($case3);
$caseWithFail = array($case4);
$caseVersionChanged = array($case5);

r($myTest->buildCaseDataTest($emptyCases, 'assigntome')) && p('') && e('0'); // 测试空用例列表返回结果
r($myTest->buildCaseDataTest($normalCases, 'assigntome')) && p('0:title') && e('正常用例1'); // 测试assigntome类型处理正常用例数据
r($myTest->buildCaseDataTest($normalCases, 'openedbyme')) && p('0:title') && e('正常用例1'); // 测试openedbyme类型处理正常用例数据
r($myTest->buildCaseDataTest($caseNeedConfirm, 'assigntome')) && p('0:status') && e('normal'); // 测试用例needconfirm标记处理
r($myTest->buildCaseDataTest($caseWithEmptyResult, 'assigntome')) && p('0:lastRunResult') && e('未执行'); // 测试用例lastRunResult为空时的默认值
r($myTest->buildCaseDataTest($caseWithFail, 'assigntome')) && p('0:lastRunResult') && e('fail'); // 测试用例lastRunResult为fail时增加失败计数
r($myTest->buildCaseDataTest($caseVersionChanged, 'assigntome')) && p('0:status') && e('原用例更新'); // 测试用例fromCaseVersion大于version时状态变更