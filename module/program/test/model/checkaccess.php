#!/usr/bin/env php
<?php

/**

title=测试 programModel::checkAccess();
timeout=0
cid=17675

- 不传入ID @1
- 传入存在ID的值 @2
- 传入存在ID的值 @6
- 不传入ID，读取session信息 @6
- 传入正确的ID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$program = zenData('project');
$program->id->range('1,2');
$program->name->range('父项目集1,父项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(2);

su('admin');

global $tester;
$tester->loadModel('program');

$programs = array(1 => 1, 2 => 2, 6 => 6);
r($tester->program->checkAccess(0,   $programs)) && p() && e('1'); //不传入ID
r($tester->program->checkAccess(2,   $programs)) && p() && e('2'); //传入存在ID的值
r($tester->program->checkAccess(6,   $programs)) && p() && e('6'); //传入存在ID的值
r($tester->program->checkAccess(0,   $programs)) && p() && e('6'); //不传入ID，读取session信息
r($tester->program->checkAccess(110, $programs)) && p() && e('1'); //传入正确的ID
