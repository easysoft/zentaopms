#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->checkCanChangeModel();
cid=1
pid=1

查看ID为11的敏捷项目是否可切换项目管理模型 >> 0
查看ID为41的瀑布项目是否可切换项目管理模型 >> 0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->checkCanChangeModel(11,  'scrum'))     && p() && e('0'); // 查看ID为11的敏捷项目是否可切换项目管理模型
r($tester->project->checkCanChangeModel(41,  'waterfall')) && p() && e('0'); // 查看ID为41的瀑布项目是否可切换项目管理模型