#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/custom.class.php';
su('admin');

/**

title=测试 customModel->getURPairs();
cid=1
pid=1

测试正常查询 >> 用户需求,用户需求,用需,史诗,用户需求

*/

$custom = new customTest();

r($custom->getURPairsTest()) && p('1,2,3,4,5') && e('用户需求,用户需求,用需,史诗,用户需求');  //测试正常查询