#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getByID;
cid=1
pid=1

*/

$project = $tester->loadModel('project');

r($project->getByID(11)) && p('code,type') && e('project1,project'); // 获取ID等于11的项目

$result = empty($project->getByID(0)) ? 'No Data!' : '';
r($result) && p() && e('No Data!'); // 获取ID等于0的项目

r($project->getByID(1, 'program')) && p('name,type') && e('项目集1,program'); // 获取ID等于0的项目集

r($project->getByID(101, 'sprint')) && p('name,type') && e('迭代1,sprint'); // 获取ID等于101的迭代
