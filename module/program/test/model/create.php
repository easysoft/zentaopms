#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1');
$program->name->range('父项目集1');
$program->type->range('program');
$program->path->range('1')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(1);

/**

title=测试 programModel::create();
cid=17678
pid=1

创建新项目集                         >> 测试新增项目集一
项目集名称为空时                     >> 『项目集名称』不能为空。
项目集的开始时间为空                 >> 『计划开始』不能为空。
项目集的完成时间为空                 >> 『计划完成』不能为空。
『计划完成』应当大于『2022-01-12』。 >> 『计划完成』应当大于『2022-01-12』。

*/

$programTester = new programModelTest();

$data = array(
    'parent'     => 0,
    'name'       => '测试新增项目集一',
    'budget'     => '0',
    'budgetUnit' => 'CNY',
    'status'     => 'wait',
    'begin'      => '2022-01-12',
    'end'        => '2022-02-12',
    'desc'       => '测试项目集描述',
    'acl'        => 'private',
    'whitelist'  => '',
    'openedBy'   => 'admin',
    'openedDate' => '2022-01-12 08:11:23',
);

$normalProgram = $data;

$emptyNameProgram = $data;
$emptyNameProgram['name'] = '';

$emptyBeginProgram = $data;
$emptyBeginProgram['begin'] = '';

$emptyEndProgram = $data;
$emptyEndProgram['end'] = '';

$beginGtEndProgram = $data;
$beginGtEndProgram['end'] = '2022-01-10';

r($programTester->createTest($normalProgram))     && p('name')             && e('测试新增项目集一');                     // 创建新项目集
r($programTester->createTest($emptyNameProgram))  && p('message[name]:0')  && e('『项目集名称』不能为空。');             // 项目集名称为空时
r($programTester->createTest($emptyBeginProgram)) && p('message[begin]:0') && e('『计划开始』不能为空。');               // 项目集的开始时间为空
r($programTester->createTest($emptyEndProgram))   && p('message[end]:0')   && e('『计划完成』不能为空。');               // 项目集的完成时间为空
r($programTester->createTest($beginGtEndProgram)) && p('message[end]:0')   && e('『计划完成』应当大于『2022-01-12』。'); // 『计划完成』应当大于『2022-01-12』。
