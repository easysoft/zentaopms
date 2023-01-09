#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-10');
$program->type->range('program');
$program->name->setFields(array(
    array('field' => 'name1', 'range' => '项目集'),
    array('field' => 'name2', 'range' => '1-10')
));
$program->gen(5);

/**

title=测试 programModel::getById();
cid=1
pid=1

通过id字段获取id=1的项目集并验证它的name。 >> 项目集1
通过id字段获取id=1000的项目集，返回空 >> 0
通过id字段获取id=0的项目集，返回空 >> 0

*/

$programTester = new programTest();

$program1 = $programTester->getByIDTest(1);
$program2 = $programTester->getByIDTest(1000);
$program3 = $programTester->getByIDTest(0);

r($program1) && p('name') && e('项目集1'); // 通过id字段获取id=1的项目集并验证它的name。
r($program2) && p()       && e('0');       // 通过id字段获取id=1000的项目集，返回空
r($program3) && p()       && e('0');       // 通过id字段获取id=0的项目集，返回空
