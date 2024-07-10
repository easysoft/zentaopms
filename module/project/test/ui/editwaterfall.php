#!/usr/bin/env php
<?php

/**

title=编辑瀑布项目测试
timeout=0
cid=73

- 创建瀑布项目成功  测试结果 @编辑项目成功

*/
chdir(__DIR__);
include '../lib/editwaterfall.ui.class.php';

zendata('projet')->loadYaml('project', false, 2)->gen(10);
$tester = new editWaterfallTester();
$tester->login();

$waterfall = array(
    array('name' => '编辑项目'.time()),
);

r($tester->editWaterfall($waterfall['0']))         && p('message')          && e('编辑项目成功');                              //编辑项目名称

$tester->closeBrowser();
