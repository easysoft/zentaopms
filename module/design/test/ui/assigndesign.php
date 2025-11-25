#!/usr/bin/env php
<?php

/**

title=指派设计测试
timeout=0
cid=2

- 指派设计
 - 测试结果 @指派成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/assigndesign.ui.class.php';

global $config;
zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(2);

$user = zenData('user');
$user->id->range('1-4');
$user->type->range('inside{3}, outside{1}');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3');
$user->realname->range('admin, user1, user2, user3');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->visions->range('rnd');
$user->gen(4);

$team = zendata('team');
$team->id->range('1-2');
$team->root->range('60');
$team->type->range('project');
$team->account->range('user1,user2');
$team->join->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$team->gen(2);

$tester = new assignDesignTester();
$tester->login();

$design = array(
    array('assignedTo' => 'user1'),
);

r($tester->assignDesign($design['0'])) && p('message,status') && e('指派成功,SUCCESS'); //指派设计

$tester->closeBrowser();
