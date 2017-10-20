#!/usr/bin/env php
<?php
<<<TC
title: testing the getWebRoot method.
TC;

/* Include the helper class. */
include '../../helper.class.php';

/* Create two objects named obj and obj2. */
$_SERVER['SCRIPT_NAME'] = '/index.php';
$webRoot = getWebRoot();
echo $webRoot . "\n";

$_SERVER['SCRIPT_NAME'] = '/zentao/index.php';
$webRoot = getWebRoot();
echo $webRoot . "\n";
