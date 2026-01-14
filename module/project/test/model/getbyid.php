#!/usr/bin/env php
<?php
/**

title=测试 projectModel::getByID();
timeout=0
cid=17819

- 获取ID为1的项目数据
 - 属性id @1
 - 属性name @敏捷项目1
 - 属性type @project
 - 属性model @scrum
- 获取ID为2的项目数据
 - 属性id @2
 - 属性name @瀑布项目2
 - 属性type @project
 - 属性model @waterfall
- 获取ID为3的项目数据
 - 属性id @3
 - 属性name @看板项目3
 - 属性type @project
 - 属性model @kanban
- 获取不存在的项目 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('project')->gen(3)->fixPath();
su('admin');

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->getByID(1))     && p('id,name,type,model') && e('1,敏捷项目1,project,scrum');        // 获取ID为1的项目数据
r($projectModel->getByID(2))     && p('id,name,type,model') && e('2,瀑布项目2,project,waterfall');    // 获取ID为2的项目数据
r($projectModel->getByID(3))     && p('id,name,type,model') && e('3,看板项目3,project,kanban');       // 获取ID为3的项目数据
r($projectModel->getByID(100))   && p()                     && e('0');                                // 获取不存在的项目
