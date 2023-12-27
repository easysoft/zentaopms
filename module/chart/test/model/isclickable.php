#!/usr/bin/env php
<?php
/**

title=测试 chartModel::isClickable();
timeout=0
cid=1

- 测试内置报表的设计按钮是否不可点击 @false
- 测试内置报表的编辑按钮是否不可点击 @false
- 测试内置报表的删除按钮是否不可点击 @false
- 测试非内置报表的设计按钮是否可点击 @true
- 测试非内置报表的编辑按钮是否可点击 @true
- 测试非内置报表的删除按钮是否可点击 @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/chart.class.php';

zdTable('user')->gen(5);
su('admin');

$chart = new chartTest();
r($chart->isClickableTest(32, 'design')) && p() && e('false');  //测试内置报表的设计按钮是否不可点击
r($chart->isClickableTest(32, 'edit'))   && p() && e('false');  //测试内置报表的编辑按钮是否不可点击
r($chart->isClickableTest(32, 'delete')) && p() && e('false');  //测试内置报表的删除按钮是否不可点击
r($chart->isClickableTest(37, 'design')) && p() && e('true');   //测试非内置报表的设计按钮是否可点击
r($chart->isClickableTest(37, 'edit'))   && p() && e('true');   //测试非内置报表的编辑按钮是否可点击
r($chart->isClickableTest(37, 'delete')) && p() && e('true');   //测试非内置报表的删除按钮是否可点击
