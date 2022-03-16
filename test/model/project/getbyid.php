#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

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

$t = new Project('admin');

$getId = array(11, 0, 1, 0, 101, 0, 131, 0, 161, 0);

r($t->getProjectByID($getId[0])) && p('code,type') && e('project1,project'); //获取ID等于11的项目
r($t->getProjectByID($getId[1])) && p('code,type') && e('0');                //获取不存在的项目
r($t->getProgramByID($getId[2])) && p('code,type') && e('program1,program'); //获取ID等于1的项目集
r($t->getProgramByID($getId[3])) && p('code,type') && e('0');                //获取不存在的项目集
r($t->getSprintByID($getId[4]))  && p('code,type') && e('project1,sprint');  //获取ID等于101的冲刺
r($t->getSprintByID($getId[5]))  && p('code,type') && e('0');                //获取不存在的冲刺
r($t->getStageByID($getId[6]))   && p('code,type') && e('project31,stage');  //获取ID等于131的阶段
r($t->getStageByID($getId[7]))   && p('code,type') && e('0');                //获取不存在的阶段
r($t->getKanbanByID($getId[8]))  && p('code,type') && e('project61,kanban'); //获取ID等于161的阶段
r($t->getKanbanByID($getId[9]))  && p('code,type') && e('0');                //获取不存在的看板