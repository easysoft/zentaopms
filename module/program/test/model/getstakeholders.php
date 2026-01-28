#!/usr/bin/env php
<?php

/**

title=测试 programModel::getStakeholders();
timeout=0
cid=17698

- 获取干系人数量 @2
- 获取干系人数量 @1
- id倒序排，获取第一个干系人真实姓名第0条的realname属性 @用户2
- id倒序排，获取第二个干系人真实姓名第1条的realname属性 @admin
- id正序排，获取第一个干系人真实姓名第0条的realname属性 @用户1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$program = zenData('project');
$program->id->range('1,2');
$program->name->range('父项目集1,父项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(2);

zenData('user')->gen(5);

$stakeholder = zenData('stakeholder');
$stakeholder->objectID->range('1,2');
$stakeholder->objectType->range('program');
$stakeholder->type->range('inside');
$stakeholder->user->range('admin,user1,user2');
$stakeholder->gen(3);

su('admin');

global $tester;
$tester->loadModel('program');
$stakeholders1 = $tester->program->getStakeholders(1, 'id_desc');
$stakeholders2 = $tester->program->getStakeholders(2, 'id_asc');

r(count($stakeholders1)) && p()             && e('2');     // 获取干系人数量
r(count($stakeholders2)) && p()             && e('1');     // 获取干系人数量
r($stakeholders1)        && p('0:realname') && e('用户2'); // id倒序排，获取第一个干系人真实姓名
r($stakeholders1)        && p('1:realname') && e('admin'); // id倒序排，获取第二个干系人真实姓名
r($stakeholders2)        && p('0:realname') && e('用户1'); // id正序排，获取第一个干系人真实姓名
