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
$tester->closeBrowser();
