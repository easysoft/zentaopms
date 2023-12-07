#!/usr/bin/env php
<?php
/**

title=测试 chartModel::isClickable();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/chart.class.php';

zdTable('user')->gen(5);
su('admin');

$chart = new chartTest();
r($chart->isClickableTest(1001, 'design')) && p() && e('false'); //测试内置报表的设计按钮是否不可点击
r($chart->isClickableTest(1001, 'edit'))   && p() && e('false'); //测试内置报表的编辑按钮是否不可点击
r($chart->isClickableTest(1001, 'delete')) && p() && e('false'); //测试内置报表的删除按钮是否不可点击
r($chart->isClickableTest(1077, 'design')) && p() && e('true');  //测试非内置报表的设计按钮是否可点击
r($chart->isClickableTest(1077, 'edit'))   && p() && e('true');  //测试非内置报表的编辑按钮是否可点击
r($chart->isClickableTest(1077, 'delete')) && p() && e('true');  //测试非内置报表的删除按钮是否可点击
