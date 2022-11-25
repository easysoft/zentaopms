#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getByID;
cid=1
pid=1

获取ID等于11的项目 >> project1,project
获取不存在的项目 >> 0
获取ID等于1的项目集 >> program1,program
获取不存在的项目集 >> 0
获取ID等于101的冲刺 >> project1,sprint
获取不存在的冲刺 >> 0
获取ID等于131的阶段 >> project31,stage
获取不存在的阶段 >> 0
获取ID等于161的阶段 >> project61,kanban
获取不存在的看板 >> 0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getByID(11, 'project')) && p('code,type') && e('project1,project'); //获取ID等于11的项目
r($tester->project->getByID(0, 'project'))  && p('code,type') && e('0');                //获取不存在的项目
r($tester->project->getByID(1, 'program'))  && p('code,type') && e('program1,program'); //获取ID等于1的项目集
r($tester->project->getByID(0, 'program'))  && p('code,type') && e('0');                //获取不存在的项目集
r($tester->project->getByID(101, 'sprint')) && p('code,type') && e('project1,sprint');  //获取ID等于101的冲刺
r($tester->project->getByID(0, 'sprint'))   && p('code,type') && e('0');                //获取不存在的冲刺
r($tester->project->getByID(131, 'stage'))  && p('code,type') && e('project31,stage');  //获取ID等于131的阶段
r($tester->project->getByID(0, 'stage'))    && p('code,type') && e('0');                //获取不存在的阶段
r($tester->project->getByID(161, 'kanban')) && p('code,type') && e('project61,kanban'); //获取ID等于161的阶段
r($tester->project->getByID(0, 'kanban'))   && p('code,type') && e('0');                //获取不存在的看板