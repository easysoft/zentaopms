#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1');
$program->name->range('项目集1');
$program->type->range('program');
$program->path->range('1')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(1);

/**

title=测试 programModel::update();
cid=1
pid=1

正常更新项目集的情况 >> 测试更新项目集
更新项目集名称为空时 >> 『项目集名称』不能为空。
当计划开始为空时更新项目集信息 >> 『计划开始』不能为空。
当计划完成为空时更新项目集信息 >> 『计划完成』不能为空。
更新未开始的项目集实际开始时间 >> doing

*/

$programTester = new programTest();

$data = array(
    'parent'       => '0',
    'name'         => '测试更新项目集',
    'begin'        => '2020-10-10',
    'end'          => '2023-09-03',
    'acl'          => 'private',
    'budget'       => '100',
    'status'       => 'wait',
    'budgetUnit'   => 'CNY',
    'syncPRJUnit'  => true,
    'exchangeRate' => '',
    'whitelist'    => array('dev10', 'dev12')
);

$normalProgram = $data;

$emptyTitleProgram = $data;
$emptyTitleProgram['name'] = '';

$emptyBeginProgram = $data;
$emptyBeginProgram['begin'] = '';

$emptyEndProgram = $data;
$emptyEndProgram['end'] = '';

$realBeganProgram = $data;
$realBeganProgram['realBegan'] = '2020-11-10';

r($programTester->updateTest(1, $normalProgram))      && p('name')              && e('测试更新项目集');           // 正常更新项目集的情况
r($programTester->updateTest(1, $emptyTitleProgram))  && p('message[name]:0')   && e('『项目集名称』不能为空。'); // 更新项目集名称为空时
r($programTester->updateTest(1, $emptyBeginProgram))  && p('message[begin]:0')  && e('『计划开始』不能为空。');   // 当计划开始为空时更新项目集信息
r($programTester->updateTest(1, $emptyEndProgram))    && p('message[end]:0')    && e('『计划完成』不能为空。');   // 当计划完成为空时更新项目集信息
r($programTester->updateTest(1, $realBeganProgram))   && p('status')            && e('doing');                    // 更新未开始的项目集实际开始时间
