#!/usr/bin/env php
<?php

/**

title=维护团队成员
timeout=0
cid=1

- 添加团队成员
 - 最终测试状态 @SUCCESS
 - 测试结果 @添加团队成员成功
- 删除团队成员
 - 最终测试状态 @SUCCESS
 - 测试结果 @删除团队成员成功
- 移除团队成员
 - 最终测试状态 @SUCCESS
 - 测试结果 @移除团队成员成功
- 复制部门成员
 - 最终测试状态 @SUCCESS
 - 测试结果 @复制部门成员成功
- 复制团队成员
 - 最终测试状态 @SUCCESS
 - 测试结果 @复制团队成员成功

*/

chdir(__DIR__);
include '../lib/managemembers.ui.class.php';
global $config;

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0');
$project->model->range('scrum{2}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 项目2');
$project->path->range('`,1,`, `,2,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(2);

$execution = zenData('project');
$execution->id->range('5-9');
$execution->project->range('1{3}, 2{2}');
$execution->type->range('sprint');
$execution->attribute->range('[]');
$execution->auth->range('[]');
$execution->parent->range('1{3}, 2{2}');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2, 项目1迭代3, 项目2迭代1, 项目2迭代2');
$execution->path->range('`,1,5,`, `,1,6,`, `,1,7,`, `,2,8,`, `,2,9,`');
$execution->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$execution->acl->range('open');
$execution->status->range('wait');
$execution->gen(5, false);

$team = zenData('team');
$team->id->range('1-100');
$team->root->range('1{3}, 2{3}, 5{2}, 6{2}, 7{2}, 8{2}');
$team->type->range('project{6}, execution{8}');
$team->account->range('user1, user2, user3, user11, user12, user13, user1, user2, user2, user3, user2, user3, user11, user12');
$team->days->range('5{3}, 6{3}, 7{5}, 0{3}');
$team->hours->range('4{3}, 3{3}, 9{5}, 2{3}');
$team->gen(14);

$dept = zenData('dept');
$dept->id->range('1-100');
$dept->name->range('部门1, 部门2, 部门1-1');
$dept->parent->range('0, 0, 1');
$dept->path->range('`,1,`, `,2,`, `,1,3,`');
$dept->grade->range('1, 1, 2');
$dept->gen(3);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0, 1{2}, 2{3}, 3{5}');
$user->account->range('admin, user1, user2, user3, user4, user5, user11, user12, user13, user14, user15');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5, USER11, USER12, USER13, USER14, USER15');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(11);

$tester = new manageMembersTester();
$tester->login();

$execution = array(
    '0' => array(
        'id'      => '5',
        'account' => 'USER5',
    ),
    '1' => array(
        'id' => '6',
    ),
    '2' => array(
        'id' => '5',
    ),
    '3' => array(
        'id'            => '7',
        'dept'          => '部门1',
        'membersExpect' => '8',
    ),
    '4' =>array(
        'id'            => '8',
        'team'          => '项目2',
        'membersExpect' => '3',
    ),
);
r($tester->add($execution['0']))             && p('status,message') && e('SUCCESS,添加团队成员成功'); //添加团队成员
r($tester->delete($execution['1']))          && p('status,message') && e('SUCCESS,删除团队成员成功'); //删除团队成员
r($tester->remove($execution['2']))          && p('status,message') && e('SUCCESS,移除团队成员成功'); //移除团队成员
r($tester->copyDeptMembers($execution['3'])) && p('status,message') && e('SUCCESS,复制部门成员成功'); //复制部门成员
r($tester->copyTeamMembers($execution['4'])) && p('status,message') && e('SUCCESS,复制团队成员成功'); //复制团队成员
$tester->closeBrowser();
