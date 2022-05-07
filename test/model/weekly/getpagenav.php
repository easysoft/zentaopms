#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getPageNav();
cid=1
pid=1

查询项目为未开始，日期为当天的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>
查询项目为未开始，日期为一周前的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>
查询项目为未开始，日期为一周后的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>
查询项目为进行中，日期为当天的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>
查询项目为进行中，日期为一周前的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>
查询项目为进行中，日期为一周后的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>
查询项目为已挂起，日期为当天的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>
查询项目为已挂起，日期为一周前的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>
查询项目为已挂起，日期为一周后的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>
查询项目为已关闭，日期为当天的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>
查询项目为已关闭，日期为一周前的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>
查询项目为已关闭，日期为一周后的数据 >> <div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>

*/
$productID = array('2', '13', '17', '18');
$date      = array('1', '2', '3');

$weekly = new weeklyTest();
r($weekly->getPageNavTest($productID[0], $date[0])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>"); //查询项目为未开始，日期为当天的数据
r($weekly->getPageNavTest($productID[0], $date[1])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>"); //查询项目为未开始，日期为一周前的数据
r($weekly->getPageNavTest($productID[0], $date[2])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目集2</a>"); //查询项目为未开始，日期为一周后的数据
r($weekly->getPageNavTest($productID[1], $date[0])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>");   //查询项目为进行中，日期为当天的数据
r($weekly->getPageNavTest($productID[1], $date[1])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>");   //查询项目为进行中，日期为一周前的数据
r($weekly->getPageNavTest($productID[1], $date[2])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目3</a>");   //查询项目为进行中，日期为一周后的数据
r($weekly->getPageNavTest($productID[2], $date[0])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>");   //查询项目为已挂起，日期为当天的数据
r($weekly->getPageNavTest($productID[2], $date[1])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>");   //查询项目为已挂起，日期为一周前的数据
r($weekly->getPageNavTest($productID[2], $date[2])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目7</a>");   //查询项目为已挂起，日期为一周后的数据
r($weekly->getPageNavTest($productID[3], $date[0])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>");   //查询项目为已关闭，日期为当天的数据
r($weekly->getPageNavTest($productID[3], $date[1])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>");   //查询项目为已关闭，日期为一周前的数据
r($weekly->getPageNavTest($productID[3], $date[2])) && p() && e("<div class='btn-group angle-btn'><a href='###' class='btn'>项目周报-项目8</a>");   //查询项目为已关闭，日期为一周后的数据