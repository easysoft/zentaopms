#!/usr/bin/env php
<?php
/**
title=isHeaderGroup
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$header1 = array();
$header1[] = array('name' => 'scope', 'title' => 'projectname', 'fixed' => 'left', 'width' => 128);
$header1[] = array('name' => '2023-12-19', 'title' => '12-19', 'headerGroup' => '2023年', 'align' => 'center', 'width' => 68);
$header1[] = array('name' => '2023-12-16', 'title' => '12-16', 'headerGroup' => '2023年', 'align' => 'center', 'width' => 68);

$header2 = array();
$header2[] = array('name' => 'scope', 'title' => 'projectname', 'fixed' => 'left', 'width' => 128);
$header2[] = array('name' => '2023-12-19', 'title' => '2023', 'align' => 'center', 'width' => 68);

$header3 = array();

r($metric->isHeaderGroup($header1)) && p() && e('1'); // 测试header1是否带分组
r($metric->isHeaderGroup($header2)) && p() && e('0'); // 测试header2是否带分组
r($metric->isHeaderGroup($header3)) && p() && e('0'); // 测试header3是否带分组
