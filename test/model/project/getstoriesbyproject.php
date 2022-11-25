#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getStoriesByProject();
cid=1
pid=1

获取项目11关联的所有产品数量 >> 2
获取项目11关联的产品ID为91的详情 >> 91
获取项目11关联的产品ID为91的需求ID >> 362,364

*/

global $tester;
$tester->loadModel('project');

$result = $tester->project->getStoriesByProject(11);

r(count($result)) && p()                && e('2');       // 获取项目11关联的所有产品数量
r($result[91])    && p('0:product')     && e('91');      // 获取项目11关联的产品ID为91的详情
r($result[91])    && p('0:storyIDList') && e('362,364'); // 获取项目11关联的产品ID为91的需求ID
