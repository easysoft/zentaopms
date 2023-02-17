#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 adminModel::getPublicClassByAPI();
cid=1
pid=1

查询公开课程数量 >> 2

*/

global $tester;
$tester->loadModel('admin');
$result = $tester->admin->getPublicClassByAPI();

r(count($result)) && p() && e('2'); // 查询公开课程数量
