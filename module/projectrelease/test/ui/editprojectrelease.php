#!/usr/bin/env php
<?php

/**
title=编辑项目发布
timeout=0
cid=73
*/
chdir(__DIR__);
include '../lib/editprojectrelease.ui.class.php';

zendata('release')->loadYaml('projectrelease', false, 1)->gen(1);
$tester = new editProjectReleaseTester();
$tester->login();
