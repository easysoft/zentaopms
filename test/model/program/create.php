#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
$db->switchDB();
su('admin');

/**

title=测试 programModel::create();
cid=1
pid=1

创建新项目集 >> 测试新增项目集一
项目集名称为空时 >> 『项目集名称』不能为空。
项目集的开始时间为空 >> 『计划开始』不能为空。
项目集的完成时间为空 >> 『计划完成』不能为空。
项目集的计划完成时间大于计划开始时间 >> 『计划完成』应当大于『2022-01-12』。
项目集的完成日期大于父项目集的完成日期(需要实时更新日期) >> 父项目集的开始日期：2022-01-16，开始日期不能小于父项目集的开始日期

*/

$program = new programTest();

$data = array(
    'parent'     => 0,
    'name'       => '测试新增项目集一',
    'budget'     => '', 
    'budgetUnit' => 'CNY',
    'begin'      => '2022-01-12',
    'end'        => '2022-02-12',
    'desc'       => '测试项目集描述',
    'acl'        => 'private',
    'whitelist'  => ''
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

$moreThanParent = $data;
$moreThanParent['parent'] = '1';
$moreThanParent['begin']  = '2018-01-01';
$moreThanParent['end']    = '2022-02-10';

r($program->create($normalProgram))     && p('name')                      && e('测试新增项目集一');                                                   // 创建新项目集
r($program->create($emptyNameProgram))  && p('message[name]:0')           && e('『项目集名称』不能为空。');                                           // 项目集名称为空时
r($program->create($emptyBeginProgram)) && p('message[begin]:0')          && e('『计划开始』不能为空。');                                             // 项目集的开始时间为空
r($program->create($emptyEndProgram))   && p('message[end]:0')            && e('『计划完成』不能为空。');                                             // 项目集的完成时间为空
r($program->create($beginGtEndProgram)) && p('message[end]:0')            && e('『计划完成』应当大于『2022-01-12』。');                               // 项目集的计划完成时间大于计划开始时间
r($program->create($moreThanParent))    && p('message:begin;message:end') && e('父项目集的开始日期：2022-01-16，开始日期不能小于父项目集的开始日期'); // 项目集的完成日期大于父项目集的完成日期(需要实时更新日期)
$db->restoreDB();