#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('execution')->gen(10);
su('admin');

/**

title=测试 executionModel::checkAccess;
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('execution');

$executions = array(101 => 101, 102 => 102, 106 => 106);
$idList   = array(0, 106, 110);
r($tester->execution->checkAccess($idList[0], $executions)) && p() && e('101'); //不传入ID
r($tester->execution->checkAccess($idList[1], $executions)) && p() && e('106'); //传入存在ID的值
r($tester->execution->checkAccess($idList[0], $executions)) && p() && e('101'); //不传入ID，读取session信息
r($tester->execution->checkAccess($idList[2], $executions)) && p() && e('101'); //传入正确的ID
