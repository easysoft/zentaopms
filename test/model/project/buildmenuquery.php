#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->buildMenuQuery();
cid=1
pid=1

获取构建的查询项目的sql的字符串长度 >> 231
获取构建的查询项目的sql的字符串长度 >> 233

*/

global $tester;
$tester->loadModel('project');

r(strlen($tester->project->buildMenuQuery(11)))  && p() && e('231'); // 获取构建的查询项目的sql的字符串长度 
r(strlen($tester->project->buildMenuQuery(100))) && p() && e('233'); // 获取构建的查询项目的sql的字符串长度