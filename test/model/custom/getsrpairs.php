#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->getSRPairs();
cid=1
pid=1

测试正常查询 >> 软件需求,研发需求,软需,故事,需求

*/

$custom = new customTest();

r($custom->getSRPairsTest()) && p('1,2,3,4,5') && e('软件需求,研发需求,软需,故事,需求');  //测试正常查询