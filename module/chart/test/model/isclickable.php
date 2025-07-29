#!/usr/bin/env php
<?php
/**

title=测试 chartModel::isClickable();
timeout=0
cid=1

- 测试内置报表的设计按钮是否不可点击 @false
- 测试内置报表的编辑按钮是否不可点击 @false
- 测试内置报表的删除按钮是否不可点击 @false
- 测试非内置报表的设计按钮是否可点击 @false
- 测试非内置报表的编辑按钮是否可点击 @false
- 测试非内置报表的删除按钮是否可点击 @false

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$chart = new chartTest();
r($chart->isClickableTest(32, 'design')) && p() && e('false');  //测试不存在的报表的设计按钮是否不可点击
r($chart->isClickableTest(32, 'edit'))   && p() && e('false');  //测试不存在的报表的编辑按钮是否不可点击
r($chart->isClickableTest(32, 'delete')) && p() && e('false');  //测试不存在的报表的删除按钮是否不可点击
r($chart->isClickableTest(10020, 'design')) && p() && e('false');   //测试内置报表的设计按钮是否可点击
r($chart->isClickableTest(10020, 'edit'))   && p() && e('false');   //测试内置报表的编辑按钮是否可点击
r($chart->isClickableTest(10020, 'delete')) && p() && e('false');   //测试内置报表的删除按钮是否可点击
