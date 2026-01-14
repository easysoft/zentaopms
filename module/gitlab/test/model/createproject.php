#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::createProject();
timeout=0
cid=16639

- 执行gitlab模块的createProjectTest方法，参数是$gitlabID, $project 第name条的0属性 @项目名称不能为空
- 执行gitlab模块的createProjectTest方法，参数是$gitlabID, $project 第path条的0属性 @项目标识串不能为空
- 执行gitlab模块的createProjectTest方法，参数是$gitlabID, $project 第name条的0属性 @项目名称不能为空
- 执行$result @0
- 执行$result @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlab = new gitlabModelTest();

$gitlabID = 1;
$project = new stdclass();
$project->name = '';
$project->path = 'unit_test_project17';
$project->description = 'unit_test_project desc';
$project->visibility = 'public';
$project->namespace_id = '1';

r($gitlab->createProjectTest($gitlabID, $project)) && p('name:0') && e('项目名称不能为空');

$project->name = 'unitTestProject17';
$project->path = '';
r($gitlab->createProjectTest($gitlabID, $project)) && p('path:0') && e('项目标识串不能为空');

$project->name = '';
$project->path = '';
r($gitlab->createProjectTest($gitlabID, $project)) && p('name:0') && e('项目名称不能为空');

$project->name = 'test@#$%^&*()';
$project->path = 'test_special_chars';
$result = $gitlab->createProjectTest($gitlabID, $project);
if(!empty($result['project_namespace.name'][0]) and $result['project_namespace.name'][0] == '已经被使用') $result = true;
if(is_array($result) && !empty($result)) $result = false;
r($result) && p() && e('0');

$project->name = 'unitTestProject18';
$project->path = 'unit_test_project18';
$result = $gitlab->createProjectTest($gitlabID, $project);
if(!empty($result['project_namespace.name'][0]) and $result['project_namespace.name'][0] == '已经被使用') $result = true;
r($result) && p() && e('1');