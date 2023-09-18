#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$program = zdTable('project');
$program->id->range('1,2');
$program->name->range('父项目集1,父项目集2');
$program->type->range('program');
$program->budget->range('900000,899900');
$program->path->range('1,2')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(2);

su('admin');
/**

title=测试 programModel::checkAccess();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('program');

$programs = array(1 => 1, 2 => 2, 6 => 6);
$idList   = array(0, 6, 110);
r($tester->program->checkAccess($idList[0], $programs)) && p() && e('1'); //不传入ID
r($tester->program->checkAccess($idList[1], $programs)) && p() && e('6'); //传入存在ID的值
r($tester->program->checkAccess($idList[0], $programs)) && p() && e('6'); //不传入ID，读取session信息
r($tester->program->checkAccess($idList[2], $programs)) && p() && e('1'); //传入正确的ID
