#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(10);
su('admin');

/**

title=测试 executionModel::checkAccess;
timeout=0
cid=16281

- 不传入ID @101
- 传入存在ID的值 @106
- 不传入ID，读取session信息 @101
- 传入不存在的ID @101
- 传入不存在的ID @101

*/

$executions = array(101 => 101, 102 => 102, 106 => 106);
$idList     = array(0, 106, 110, 120);

$executionTester = new executionModelTest();
r($executionTester->checkAccessTest($idList[0], $executions)) && p() && e('101'); //不传入ID
r($executionTester->checkAccessTest($idList[1], $executions)) && p() && e('106'); //传入存在ID的值
r($executionTester->checkAccessTest($idList[0], $executions)) && p() && e('101'); //不传入ID，读取session信息
r($executionTester->checkAccessTest($idList[2], $executions)) && p() && e('101'); //传入不存在的ID
r($executionTester->checkAccessTest($idList[3], $executions)) && p() && e('101'); //传入不存在的ID
