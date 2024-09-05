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
