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

su('admin');
/**

title=测试 programModel::saveState();
cid=1
pid=1

项目集存在且正常访问                   >> html
项目集不存在                           >> 0
项目集存在，但不在可以查看的项目集列表 >> 1
项目集存在，且在可以查看的项目集列表   >> 2
项目集不存在，且有可以查看的项目集列表 >> 2

*/

$programTester = new programTest();
ob_start();
$programTester->saveStateTest(1);
$program1 = ob_get_clean();
$program2 = $programTester->saveStateTest(0);
$program3 = $programTester->saveStateTest(1, array(1 => ''));
$program4 = $programTester->saveStateTest(3, array(2 => ''));
$program5 = $programTester->saveStateTest(0, array(2 => ''));

r(substr($program1, 1, 4)) && p() && e('html'); // 项目集存在且正常访问
r($program2) && p() && e('0');                  // 项目集不存在
r($program3) && p() && e('1');                  // 项目集存在，但不在可以查看的项目集列表
r($program4) && p() && e('2');                  // 项目集存在，且在可以查看的项目集列表
r($program5) && p() && e('2');                  // 项目集不存在，且有可以查看的项目集列表
