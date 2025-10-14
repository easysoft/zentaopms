#!/usr/bin/env php
<?php

/**

title=项目发布详情
timeout=0
cid=73

- 项目发布详情检查
 - 测试结果 @项目发布详情查看成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/view.ui.class.php';

zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
$tester = new viewTester();
$tester->login();

r($tester->checkReleaseView()) && p('message,status') && e('项目发布详情查看成功,SUCCESS');    //项目发布详情检查

$tester->closeBrowser();
