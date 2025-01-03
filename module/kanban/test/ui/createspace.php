#!/usr/bin/env php
<?php
/**
title=创建空间
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/createspace.ui.class.php';
$tester = new createSpaceTester();
$tester->login();
$space  = new stdClass();
$space->title = '';
$tester->closeBrowser();
