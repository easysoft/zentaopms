#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getPageNav();
cid=1
pid=1

查询项目为未开始，日期为当天的数据 >> 周报-项目9
查询项目为未开始，日期为一周前的数据 >> 周报-项目9
查询项目为进行中，日期为当天的数据 >> 周报-项目3
查询项目为进行中，日期为一周前的数据 >> 周报-项目3
查询项目为已挂起，日期为当天的数据 >> 周报-项目7
查询项目为已挂起，日期为一周前的数据 >> 周报-项目7
查询项目为已关闭，日期为当天的数据 >> 周报-项目8
查询项目为已关闭，日期为一周前的数据 >> 周报-项目8

*/
$productID = array('19', '13', '17', '18');
$date      = array('2022-05-07', '');

$weekly = new weeklyTest();
r($weekly->getPageNavTest($productID[0], $date[0])) && p() && e('周报-项目9');  //查询项目为未开始，日期为当天的数据
r($weekly->getPageNavTest($productID[0], $date[1])) && p() && e('周报-项目9');  //查询项目为未开始，日期为一周前的数据
r($weekly->getPageNavTest($productID[1], $date[0])) && p() && e('周报-项目3');  //查询项目为进行中，日期为当天的数据
r($weekly->getPageNavTest($productID[1], $date[1])) && p() && e('周报-项目3');  //查询项目为进行中，日期为一周前的数据
r($weekly->getPageNavTest($productID[2], $date[0])) && p() && e('周报-项目7');  //查询项目为已挂起，日期为当天的数据
r($weekly->getPageNavTest($productID[2], $date[1])) && p() && e('周报-项目7');  //查询项目为已挂起，日期为一周前的数据
r($weekly->getPageNavTest($productID[3], $date[0])) && p() && e('周报-项目8');  //查询项目为已关闭，日期为当天的数据
r($weekly->getPageNavTest($productID[3], $date[1])) && p() && e('周报-项目8');  //查询项目为已关闭，日期为一周前的数据