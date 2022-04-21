#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getWorkload();
cid=1
pid=1

测试获取dept 10 的工作量 >> user92:count:7,manhour:23;
测试获取dept 49 的工作量 >> po82:count:6,manhour:12;
测试获取dept 10 非指派人 的工作量 >> user93:count:0,manhour:0;user94:count:0,manhour:0;
测试获取dept 49 非指派人 的工作量 >> po83:count:0,manhour:0;po84:count:0,manhour:0;

*/
$dept   = array(10, 49);
$assign = 'noassign';

$report = new reportTest();

r($report->getWorkloadTest($dept[0]))          && p() && e('user92:count:7,manhour:23;');                         // 测试获取dept 10 的工作量
r($report->getWorkloadTest($dept[1]))          && p() && e('po82:count:6,manhour:12;');                           // 测试获取dept 49 的工作量
r($report->getWorkloadTest($dept[0], $assign)) && p() && e('user93:count:0,manhour:0;user94:count:0,manhour:0;'); // 测试获取dept 10 非指派人 的工作量
r($report->getWorkloadTest($dept[1], $assign)) && p() && e('po83:count:0,manhour:0;po84:count:0,manhour:0;');     // 测试获取dept 49 非指派人 的工作量