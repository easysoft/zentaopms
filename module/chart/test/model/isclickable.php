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
r($chart->isClickableTest(1001)) && p() && e('false'); //测试内置报表的操作按钮是否不可点击
r($chart->isClickableTest(1077)) && p() && e('true'); //测试非内置报表的操作按钮是否可点击
