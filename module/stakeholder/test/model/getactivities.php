#!/usr/bin/env php
<?php

/**

title=测试 stakeholderModel::getActivities();
timeout=0
cid=18430

- 步骤1：获取正常活动数据的键值对属性1 @活动名称1
- 步骤2：验证返回数据的数量 @20
- 步骤3：测试无活动数据时的处理 @0
- 步骤4：验证deleted状态过滤功能 @3
- 步骤5：测试返回数据的格式和结构
 - 属性1 @正常活动1
 - 属性2 @正常活动2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stakeholder.unittest.class.php';

// 准备正常活动数据
$activity = zenData('activity');
$activity->id->range('1-20');
$activity->name->range('活动名称1,活动名称2,活动名称3,活动名称4,活动名称5,活动名称6,活动名称7,活动名称8,活动名称9,活动名称10,活动名称11,活动名称12,活动名称13,活动名称14,活动名称15,活动名称16,活动名称17,活动名称18,活动名称19,活动名称20');
$activity->deleted->range('0');
$activity->status->range('active');
$activity->process->range('1-5');
$activity->gen(20);

su('admin');

$stakeholderTest = new stakeholderTest();

r($stakeholderTest->getActivitiesTest()) && p('1') && e('活动名称1'); // 步骤1：获取正常活动数据的键值对
r(count($stakeholderTest->getActivitiesTest())) && p() && e('20'); // 步骤2：验证返回数据的数量

// 清空数据，测试无活动数据情况
zenData('activity')->gen(0);
r(count($stakeholderTest->getActivitiesTest())) && p() && e('0'); // 步骤3：测试无活动数据时的处理

// 准备包含已删除活动的数据
$activity = zenData('activity');
$activity->id->range('1-5');
$activity->name->range('正常活动1,正常活动2,已删除活动1,正常活动3,已删除活动2');
$activity->deleted->range('0,0,1,0,1');
$activity->status->range('active');
$activity->process->range('1-3');
$activity->gen(5);
r(count($stakeholderTest->getActivitiesTest())) && p() && e('3'); // 步骤4：验证deleted状态过滤功能

// 验证返回数据格式
$activities = $stakeholderTest->getActivitiesTest();
r($activities) && p('1,2') && e('正常活动1,正常活动2'); // 步骤5：测试返回数据的格式和结构