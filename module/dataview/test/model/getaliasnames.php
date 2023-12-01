#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::getAliasNames();
timeout=0
cid=1

- 获取statement中的表名和表别名。
 - 属性t1 @bug
 - 属性t2 @project

*/
global $tester;
$tester->loadModel('dataview');

$statement = new stdclass();

$from = new stdclass();
$from->table = 'bug';
$from->alias = 't1';
$statement->from = array($from);

$join = new stdclass();
$join->expr = new stdclass();
$join->expr->table = 'project';
$join->expr->alias = 't2';
$statement->join = array($join);

r($tester->dataview->getAliasNames($statement, array('bug' => '', 'project' => ''))) && p('t1;t2')  && e('bug,project');  //获取statement中的表名和表别名。
