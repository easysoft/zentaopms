#!/usr/bin/env php
<?php

/**

title=设置瀑布项目阶段测试
timeout=0
cid=1

*/
chdir(__DIR__);
include '../lib/createstage.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
$tester = new createstageTester();
$tester->login();
