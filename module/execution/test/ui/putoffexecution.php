<?php

/**
title=挂起执行
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/putoffexecution.ui.class.php';

zenData('project')->loadYaml('execution', false, 2)->gen(10);
$tester = new putoffexecutionTester();
$tester->login();

$execution = array(
    '0' => array(),
    '1' => array(
        'begin' => date('Y-m-d', strtotime('-1 days')),
        'end'   => date('Y-m-d', strtotime('+1 days')),
    ),
    '2' => array(
        'begin' = '',
    ),
    '3' =>array(
        'end' = '',
    ),
    '4' => array(
        'begin' => date('Y-m-d', strtotime('-10 months')),
    ),
    '5' => array(
        'end' => date('Y-m-d', strtotime('+10 months')),
    ),
);
