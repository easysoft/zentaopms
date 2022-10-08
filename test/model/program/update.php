#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
$db->switchDB();
su('admin');

/**

title=测试 programModel::update();
cid=1
pid=1

正常更新项目集的情况 >> 测试更新项目集十
更新项目集名称为空时 >> 『项目集名称』不能为空。
当计划开始为空时更新项目集信息 >> 『计划开始』不能为空。
当计划完成为空时更新项目集信息 >> 『计划完成』不能为空。
更新未开始的项目集实际开始时间 >> doing

*/

$program = new programTest();

$data = array(
    'parent' => '0',
    'name' => '测试更新项目集十',
    'begin' => '2020-10-10',
    'end' => '2022-09-03',
    'acl' => 'private',
    'budget' => '100',
    'budgetUnit' => 'CNY',
    'syncPRJUnit' => true,
    'exchangeRate' => '',
    'whitelist' => array('dev10', 'dev12')
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

r($program->update(10, $normalProgram))      && p('name')              && e('测试更新项目集十');         // 正常更新项目集的情况
r($program->update(10, $emptyTitleProgram))  && p('message[name]:0')   && e('『项目集名称』不能为空。'); // 更新项目集名称为空时
r($program->update(10, $emptyBeginProgram))  && p('message[begin]:0')  && e('『计划开始』不能为空。');   // 当计划开始为空时更新项目集信息
r($program->update(10, $emptyEndProgram))    && p('message[end]:0')       && e('『计划完成』不能为空。');   // 当计划完成为空时更新项目集信息
r($program->update(10, $realBeganProgram))   && p('status')            && e('doing');                    // 更新未开始的项目集实际开始时间
$db->restoreDB();
