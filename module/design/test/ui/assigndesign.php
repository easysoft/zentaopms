#!/usr/bin/env php
<?php

/**

title=指派设计测试
timeout=0
cid=2

- 指派设计最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/assigndesign.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(2);

$team = zendata('team');
$team->id->range('1');
$team->root->range('60');
$team->type->range('project');
$team->account->range('admin');
$team->join->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$team->gen(1);

$tester = new assignDesignTester();
$tester->login();

$design = array(
    array('assignedTo' => 'admin'),
);

r($tester->assignDesign($design['0'])) && p('status') && e('SUCCESS'); //指派设计

$tester->closeBrowser();
