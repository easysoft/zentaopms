#!/usr/bin/env php
<?php

/**
title=项目团队成员列表
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/team.ui.class.php';

zendata('project')->loadYaml('project', false, 1)->gen(1);
zendata('team')->loadYaml('team', false, 1)->gen(5);
$tester = new teamTester();
$tester->login();

r($tester->removeMembers()) && p('message') && e('项目团队成员移除成功');   //移除项目已有的团队成员

$tester->closeBrowser();
