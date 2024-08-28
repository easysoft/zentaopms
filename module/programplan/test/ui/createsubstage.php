#!/usr/bin/env php
<?php

/**

title=创建瀑布项目子阶段测试
timeout=0
cid=2

*/
chdir(__DIR__);
include '../lib/createsubstage.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
$tester = new createsubstageTester();
$tester->login();

$waterfall = array(
    array('name_0' => '', 'begin_0' => date('Y-m-d'), 'end_0' => date('Y-m-d', strtotime('+30 days'))),
    array('name_0' => '需求的子阶段1', 'begin_0' => '', 'end_0' => date('Y-m-d', strtotime('+30 days'))),
    array('name_0' => '需求的子阶段1', 'begin_0' => date('Y-m-d'), 'end_0' => ''),
    array('name_0' => '需求的子阶段1', 'begin_0' => '2020-11-10', 'end_0' => '2020-11-09'),
    array('name_0' => '需求的子阶段1', 'begin_0' => '2020-11-11', 'end_0' => '2020-11-17'),
);

r($tester->createSubStage($waterfall['0'])) && p('message,status') && e('创建阶段表单页提示信息正确，success');                //校验阶段名称不能为空
r($tester->createSubStage($waterfall['1'])) && p('message,status') && e('创建阶段表单页提示信息正确，success');                //校验计划开始必填
r($tester->createSubStage($waterfall['2'])) && p('message,status') && e('创建阶段表单页提示信息正确，success');                //校验计划完成必填
r($tester->createSubStage($waterfall['3'])) && p('message,status') && e('创建阶段表单页提示信息正确，success');                //校验计划完成必须大于计划开始
r($tester->createSubStage($waterfall['4'])) && p('status') && e('success');                                                    //创建需求子阶段

$tester->closeBrowser();
