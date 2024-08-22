#!/usr/bin/env php
<?php

/**
title=创建项目发布
timeout=0
cid=73
*/
chdir(__DIR__);
include '../lib/createprojectrelease.ui.class.php';

//zendata('project')->loadYaml('project', false, 2)->gen(10);
$tester = new createProjectReleaseTester();
$tester->login();
