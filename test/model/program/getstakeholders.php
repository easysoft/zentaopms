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

title=测试 programModel::getStakeholders();
cid=1
pid=1

获取干系人数量                     >> 2
获取干系人数量                     >> 1
id倒序排，获取第一个干系人真实姓名 >> 用户2
id正序排，获取第一个干系人真实姓名 >> 用户1

*/

$programTester = new programTest();
$stakeholders1 = $programTester->getStakeholdersTest(1, 'id_desc');
$stakeholders2 = $programTester->getStakeholdersTest(2, 'id_asc');

r(count($stakeholders1)) && p()             && e('2');     // 获取干系人数量
r(count($stakeholders2)) && p()             && e('1');     // 获取干系人数量
r($stakeholders1)        && p('0:realname') && e('用户2'); // id倒序排，获取第一个干系人真实姓名
r($stakeholders2)        && p('0:realname') && e('用户1'); // id正序排，获取第一个干系人真实姓名
