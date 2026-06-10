#!/usr/bin/env php
<?php

/**

title=测试 chartModel::isClickable();
timeout=0
cid=15579

- 测试不存在的图表按钮是否不可点击 @0
- 测试内置图表的设计按钮是否不可点击 @0
- 测试内置图表的编辑按钮是否不可点击 @0
- 测试内置图表的删除按钮是否不可点击 @0
- 测试自定义图表的设计按钮是否可点击 @1
- 测试自定义图表的编辑按钮是否可点击 @1
- 测试自定义图表的删除按钮是否可点击 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('chart')->loadYaml('chart')->gen(50);
zenData('module')->loadYaml('module')->gen(27)->fixPath();
zenData('user')->gen(5);

su('admin');

$chart = new chartModelTest();
r($chart->isClickableTest(10086, 'design')) && p() && e('0');   //测试不存在的图表按钮是否不可点击
r($chart->isClickableTest(44, 'design'))    && p() && e('0');   //测试内置图表的设计按钮是否不可点击
r($chart->isClickableTest(44, 'edit'))      && p() && e('0');   //测试内置图表的编辑按钮是否不可点击
r($chart->isClickableTest(44, 'delete'))    && p() && e('0');   //测试内置图表的删除按钮是否不可点击
r($chart->isClickableTest(45, 'design'))    && p() && e('1');   //测试自定义图表的设计按钮是否可点击
r($chart->isClickableTest(45, 'edit'))      && p() && e('1');   //测试自定义图表的编辑按钮是否可点击
r($chart->isClickableTest(45, 'delete'))    && p() && e('1');   //测试自定义图表的删除按钮是否可点击
