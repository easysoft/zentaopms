#!/usr/bin/env php
<?php

/**
title=项目团队管理
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/managemembers.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('user')->loadYaml('account', false, 1)->gen(5);
zendata('dept')->loadYaml('dept', false, 1)->gen(1);
$tester = new manageMembersTester();
$tester->login();

//设置敏捷项目执行数据
$members = array(
    array('account' => '用户1', 'role' => '开发人员', 'day' => '7', 'hours' => '3'),
);

r($tester->addMembers($members['0'])) && p('message') && e('项目团队成员添加成功');  //添加项目团队成员
r($tester->deleteMembers())           && p('status')  && e('SUCCESS');               //删除项目已有的团队成员
r($tester->copyDeptMembers())         && p('status')  && e('SUCCESS');               //复制部门成员

$tester->closeBrowser();
