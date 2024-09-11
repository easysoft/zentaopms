#!/usr/bin/env php
<?php

/**
title=项目发布详情
timeout=0
cid=73
*/
chdir(__DIR__);
include '../lib/view.ui.class.php';

zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
$tester = new viewTester();
$tester->login();

r($tester->checkReleaseView()) && p('status') && e('SUCCESS');    //项目发布详情检查

$tester->closeBrowser();
