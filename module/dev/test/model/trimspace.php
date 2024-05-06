#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

/**

title=测试 devModel::trimSpace();
cid=1
pid=1

获取去除空白字符的数据 >> test

*/

global $tester;
$tester->loadModel('dev');
r($tester->dev->trimSpace('* test ')) && p() && e('test'); //获取去除空白字符的数据
