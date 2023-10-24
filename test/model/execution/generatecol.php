#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->generateCol();
cid=1
pid=1

*/

$executionTester = new executionTest();

r($executionTester->generateColTest('id_desc')) && p("0:sortType")     && e('down'); // 按ID倒序排，查看获取到的sortType 
r($executionTester->generateColTest('id_asc'))  && p("0:sortType")     && e('up'); // 按ID正序排，查看获取到的sortType 
r($executionTester->generateColTest('id_desc')) && p("2:name;2:title") && e('code;执行代号'); // 查看获取到的第三个字段的name和title 
