#!/usr/bin/env php
<?php
<<<TC
title: testing the getWebRoot method.
TC;

/* Include the helper class. */
include '../../helper.class.php';

/* Create two objects named obj and obj2. */
define('IN_SHELL', true);

$_SERVER['argv'][1] = 'http://localhost';
$webRoot = getWebRoot();
echo 'http://localhost webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/';
$webRoot = getWebRoot();
echo 'http://localhost/ webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/my/';
$webRoot = getWebRoot();
echo 'http://localhost/my/ webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/my-todo.html';
$webRoot = getWebRoot();
echo 'http://localhost/my-todo.html webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/index.php?m=my&f=index';
$webRoot = getWebRoot();
echo 'http://localhost/index.php?m=my&f=index webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/zentao/my/';
$webRoot = getWebRoot();
echo 'http://localhost/zentao/my/ webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/zentao/my-todo.html';
$webRoot = getWebRoot();
echo 'http://localhost/zentao/my-todo.html webRoot is ' . $webRoot . "\n";

$_SERVER['argv'][1] = 'http://localhost/zentao/index.php?m=my&f=index';
$webRoot = getWebRoot();
echo 'http://localhost/zentao/index.php?m=my&f=index webRoot is ' . $webRoot . "\n";
