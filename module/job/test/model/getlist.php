#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getList();
cid=1
pid=1

测试获取列表的个数，目前只有两个 >> 2
测试获取列表某个job的名称信息 >> 这是一个Job1

*/

$job  = new jobTest();
$list = $job->getListTest();

r(count($list)) && p()         && e('2');             //测试获取列表的个数，目前只有两个
r($list)        && p('1:name') && e('这是一个Job1');  //测试获取列表某个job的名称信息
