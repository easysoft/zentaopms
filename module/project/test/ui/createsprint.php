#!/usr/bin/env php
<?php

/**
title=敏捷项目下创建迭代
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/createsprint.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
$tester = new createSprintTester();
$tester->login();
