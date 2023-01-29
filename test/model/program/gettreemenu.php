#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1-5');
$program->name->range('项目集1,项目集2');
$program->type->range('program');
$program->status->range('wait');
$program->parent->range('0,0,1,1,2');
$program->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(5);

/**

title=测试 programModel::getTreeMenu();
cid=1
pid=1

查看返回的字符个数 >> 项目集1项目集2

*/
$programTester = new programTest();
$programs1     = $programTester->getTreeMenuTest(1);
$programs1     = preg_replace('/\s*/', '', strip_tags($programs1));

r($programs1) && p() && e('项目集1项目集2'); // 查看返回的字符个数
