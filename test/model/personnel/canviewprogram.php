#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('test2');

/**

title=测试 personnelModel->canViewProgram();
cid=1
pid=1

看是否具有访问权限有的情况 >> 1
看是否具有访问权限no的情况 >> 1

*/

$personnel = new personnelTest('admin');

$propramID = array();
$programID[0] = 1;

$account = array();
$account[0] = 'test2';

r($personnel->canViewProgramTest(1, 'test2')) && p() && e('1');     //看是否具有访问权限有的情况
r($personnel->canViewProgramTest(10000, 'test2')) && p() && e('1'); //看是否具有访问权限no的情况