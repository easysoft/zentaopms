#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-5');
$project->project->range('0');
$project->name->prefix("项目")->range('1-5');
$project->code->prefix("project")->range('1-5');
$project->model->range("scrum,waterfall,kanban");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("1");
$project->status->range("wait");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");

$project->gen(4);

/**

title=测试taskModel->batchUpdate();
timeout=0
cid=1

*/

$project = new Project();
$projectIdList = array(1, 2, 3);

$data[1] = new stdClass();
$data[1]->name   = '批量修改项目11';
$data[1]->parent = 1;
$data[1]->PM     = 'user10';
$data[1]->begin  = '2022-02-08';
$data[1]->end    = '2022-04-13';
$data[1]->day    = 10;
$data[1]->acl    = 'open';

$data[2] = new stdClass();
$data[2]->name   = '批量修改项目12';
$data[2]->parent = 2;
$data[2]->PM     = 'user11';
$data[2]->begin  = '2022-03-05';
$data[2]->end    = '2022-04-13';
$data[2]->day    = 10;
$data[2]->acl    = 'private';

$data[3] = new stdClass();
$data[3]->name   = '批量修改项目13';
$data[3]->parent = 3;
$data[3]->PM     = 'user13';
$data[3]->begin  = '2022-02-19';
$data[3]->end    = '2022-04-13';
$data[3]->day    = 14;
$data[3]->acl    = 'program';

$projects = $project->batchUpdate($data);

r(count($projects)) && p()                             && e('3');                                          // 查看被编辑了的项目数量
r($projects)        && p('1:name,parent,PM,begin,acl') && e('批量修改项目11,1,user10,2022-02-08,open');    // 查看被编辑了的项目11详情
r($projects)        && p('2:name,parent,PM,begin,acl') && e('批量修改项目12,2,user11,2022-03-05,private'); // 查看被编辑了的项目12详情
r($projects)        && p('3:name,parent,PM,begin,acl') && e('批量修改项目13,3,user13,2022-02-19,program'); // 查看被编辑了的项目13详情

$data = array();
$data[4] = new stdClass();
$data[4]->name   = '批量修改项目14';
$data[4]->parent = 4;
$data[4]->PM     = 'user14';
$data[4]->begin  = '2023-02-19';
$data[4]->end    = '2022-04-13';
$data[4]->day    = 14;
$data[4]->acl    = 'program';

$projects = $project->batchUpdate($data);
r($projects) && p('message[end]:0') && e('ID4『计划完成』应当大于『2023-02-19』。'); // 异常情况
