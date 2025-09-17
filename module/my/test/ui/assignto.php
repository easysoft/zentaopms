#!/usr/bin/env php
<?php

/**

title=指派待办测试
timeout=0
cid=2

- 指派待办最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/assignto.ui.class.php';

global $config;

$user = zenData('user');
$user->id->range('1-5');
$user->dept->range('1');
$user->account->range('admin, user1, user2, user3, user4');
$user->realname->range('admin, 用户1, 用户2, 用户3, 用户4');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);

$todo = zendata('todo');
$todo->id->range('1');
$todo->account->range('admin');
$todo->date->range('(-1M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$todo->type->range('custom');
$todo->name->range('待办1');
$todo->status->range('wait');
$todo->private->range('0');
$todo->assignedTo->range('admin');
$todo->vision->range('rnd');
$todo->gen(1);

$tester = new assignToTester();
$tester->login();

$todo = array(
    array('assignTo' => '用户1', 'assignDate' => date('Y-m-d', strtotime('+2 days'))),
);

r($tester->assignTo($todo['0'])) && p('message,status') && e('指派他人待办成功,SUCCESS'); //指派他人待办

$tester->closeBrowser();
