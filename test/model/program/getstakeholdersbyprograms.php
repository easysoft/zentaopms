#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

$program = zdTable('project');
$program->id->range('1,2');
$program->name->range('父项目集1,父项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(2);

zdTable('user')->gen(5);

$stakeholder = zdTable('stakeholder');
$stakeholder->objectID->range('1,2');
$stakeholder->objectType->range('program');
$stakeholder->type->range('inside');
$stakeholder->user->range('admin,user1,user2');
$stakeholder->gen(3);

su('admin');

/**

title=测试 programModel::getStakeholdersByPrograms();
cid=1
pid=1

获取项目集1的干系人名单          >> admin
获取项目集2的干系人名单          >> user1
获取项目集1和项目集2的干系人名单 >> admin
获取项目集1的干系人个数          >> 2
获取项目集1、2的干系人个数       >> 3

*/

global $tester;
$tester->loadModel('program');

r($tester->program->getStakeholdersByPrograms('1'))          && p('0:account') && e('admin'); // 获取项目集1的干系人名单
r($tester->program->getStakeholdersByPrograms('2'))          && p('0:account') && e('user1'); // 获取项目集2的干系人名单
r($tester->program->getStakeholdersByPrograms('1,2'))        && p('0:account') && e('admin'); // 获取项目集1和项目集2的干系人名单
r(count($tester->program->getStakeholdersByPrograms('1')))   && p('')          && e('2');     // 获取项目集1的干系人个数
r(count($tester->program->getStakeholdersByPrograms('1,2'))) && p('')          && e('3');     // 获取项目集1、2的干系人个数
