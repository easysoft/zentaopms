#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=测试 jobModel->checkParameterizedBuild();
cid=1
pid=1

*/
$jenkins1 = new stdclass();
$jenkins1->url      = 'pms.cc.cc';
$jenkins1->account  = '123456';
$jenkins1->token    = 'zxd';
$jenkins1->pipeline = '12';

$jenkins2 = new stdclass();
$jenkins2->url      = 'pms.cc.cc';
$jenkins2->account  = '123456';
$jenkins2->password = '8bb44ffbc4b42fcbb3152cc05fd21c67';
$jenkins2->token    = '';
$jenkins2->pipeline = '11';

$jenkins3 = new stdclass();
$jenkins3->url      = '';
$jenkins3->account  = '';
$jenkins3->password = '';
$jenkins3->token    = '';
$jenkins3->pipeline = '';

$compile = new jobTest();

r($compile->checkParameterizedBuildTest('pms.cc.cc/job/12/api/json', '123456:zxd')) && p('') && e('1');
r($compile->checkParameterizedBuildTest('pms.cc.cc/job/11/api/json', '123456:8bb44ffbc4b42fcbb3152cc05fd21c67')) && p('') && e('1');
r($compile->checkParameterizedBuildTest('', '')) && p('') && e('0');
