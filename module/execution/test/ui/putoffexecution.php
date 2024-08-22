<?php

/**
title=挂起执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/putoffexecution.ui.class.php';

$tester = new putoffexecutionTester();
$tester->login();

$execution = array(
    '0' => array(
        'begin' => '',
        'end'   => '',
    ),
);
