#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->saveLibState();
cid=1
pid=1

保存用例库状态之后返回值 >> 201

*/

$caselib = new caselibTest();
$libs    = $tester->loadModel('caselib')->getLibraries();
$id      = $caselib->saveLibStateTest(201, $libs);

r($id) && p() && e('201'); //保存用例库状态之后返回值