#!/usr/bin/env php
<?php

/**

title=项目发布列表
timeout=0
cid=73

- 发布一个发布最终测试状态 @SUCCESS
- 停止维护项目发布最终测试状态 @SUCCESS
- 激活项目发布最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/browse.ui.class.php';

zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
$tester = new browseTester();
$tester->login();

r($tester->releaseRelease())   && p('status') && e('SUCCESS');   // 发布一个发布
r($tester->terminateRelease()) && p('status') && e('SUCCESS');   // 停止维护项目发布
r($tester->activeRelease())    && p('status') && e('SUCCESS');   // 激活项目发布

$tester->closeBrowser();
