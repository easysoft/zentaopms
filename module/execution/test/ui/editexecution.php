#!/usr/bin/env php
<?php

/**

title=编辑执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/editexecution.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
$tester = new editExecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'name'     => '编辑测试执行1',
        'project'  => '敏捷项目1',
        'begin'    => date('Y-m-d', strtotime('+1 days')),
        'end'      => date('Y-m-d', strtotime('+3 days')),
        'products' => '',
    ),
);

r($tester->edit($execution['0'])) && p('status,message') && e('SUCCESS,编辑执行成功');               //创建执行成功
$tester->closeBrowser();
