#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getThisSunday();
cid=1
pid=1

查询日期为星期日 >> 2022-05-08
查询日期为其他 >> 2022-05-01
查询日期为空 >> 2022-03-06

*/
$date = array('2022-05-08', '2022-04-29', '2022-03-01');

$weekly = new weeklyTest();

r($weekly->getThisSundayTest($date[0])) && p() && e('2022-05-08'); //查询日期为星期日
r($weekly->getThisSundayTest($date[1])) && p() && e('2022-05-01'); //查询日期为其他
r($weekly->getThisSundayTest($date[2])) && p() && e('2022-03-06'); //查询日期为空