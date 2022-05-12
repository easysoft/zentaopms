#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->getSuccessJobs();
cid=1
pid=1

*/

$compile = new compileTest();

r($compile->getSuccessJobsTest('1,2')) && p('1') && e('1'); //检查是否能拿取到数据
r($compile->getSuccessJobsTest('3,4')) && p('')  && e('0'); //检查传一个不存在的jobidlist会返回什么
