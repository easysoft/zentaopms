#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/my.class.php';
su('admin');

/**

title=测试 myModel->getTestcasesBySearch();
cid=1
pid=1

当插入一条action为admin指派了task的动态时，查询结果为1条数据 >> 1
当插入一条action为admin指派了bug的动态时，查询结果为1条数据 >> 1

*/

$my    = new myTest();
$type  = array('contribute', 'openedbyme');
$order = 'id_desc';
a(1111);exit;
r($my->getTestcasesBySearchTest($type[0], $order))  && p() && e('1'); // 
r($my->getTestcasesBySearchTest($type[1], $order))  && p() && e('1'); // 

