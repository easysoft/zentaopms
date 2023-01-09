#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

$program = zdTable('project');
$program->id->range('1');
$program->name->range('父项目集1');
$program->type->range('program');
$program->path->range('1')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(1);

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

创建id=1的项目集的干系人并查看数量。 >> 2
创建id=1的项目集的干系人dev1,dev2并查看Account。 >> dev1;dev2

*/

$programTester = new programTest();

$accounts = array('dev1', 'dev2');
$result   = $programTester->createStakeholderTest(1, $accounts);

r(count($result)) && p('')                    && e('2');         // 创建id=1的项目集的干系人并查看数量。
r($result)        && p('0:account;1:account') && e('dev1;dev2'); // 创建id=1的项目集的干系人dev1,dev2并查看Account。
