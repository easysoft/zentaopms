#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->getByID();
cid=1
pid=1

正常根据id查询 >> 这是一个设计1
查询不存在的设计id >> 0

*/
global $tester;
$design = $tester->loadModel('design');

$designIDList = array('0', '1');

r($design->getByID($designIDList[1])) && p('name') && e('这是一个设计1');//正常根据id查询
r($design->getByID($designIDList[0])) && p()       && e('0');            //查询不存在的设计id