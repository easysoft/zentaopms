#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getStoriesBySearch();
cid=1
pid=1

获取story状态的项目 >> 软件需求350,draft
获取story状态的项目 >> 软件需求302,draft

*/

$my       = new myTest();
$typeList = array('contribute', 'other');
$orderBy  = array('id_desc', 'id_asc');

$story1 = $my->getStoriesBySearchTest(0, $typeList[0], $orderBy[0]);
$story2 = $my->getStoriesBySearchTest(0, $typeList[1], $orderBy[1]);

r($story1) && p('350:title,status') && e('软件需求350,draft');//获取story状态的项目
r($story2) && p('302:title,status') && e('软件需求302,draft');//获取story状态的项目