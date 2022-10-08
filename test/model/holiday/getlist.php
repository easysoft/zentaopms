#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getList();
cid=1
pid=1

返回2022年的holiday list >> 100
返回2022年2月的holiday list >> 0
返回2022年5月的holiday list >> 100
返回2002年的holiday list >> 0
返回2022年所有类型的holiday list >> 100
返回2022年类型为holiday的holiday list >> 50
返回2022年类型为working的holiday list >> 50
返回2022年类型为空holiday list >> 100

*/

$holiday = new holidayTest();
$t_numyear = array('2022', '2022-02', '2022-05', '2002', '');
$t_type    = array('all', 'holiday', 'working', '');

r($holiday->getListTest($t_numyear[0]))             && p() && e('100'); // 返回2022年的holiday list
r($holiday->getListTest($t_numyear[1]))             && p() && e('0'); // 返回2022年2月的holiday list
r($holiday->getListTest($t_numyear[2]))             && p() && e('100'); // 返回2022年5月的holiday list
r($holiday->getListTest($t_numyear[3]))             && p() && e('0'); // 返回2002年的holiday list
r($holiday->getListTest($t_numyear[0], $t_type[0])) && p() && e('100'); // 返回2022年所有类型的holiday list
r($holiday->getListTest($t_numyear[0], $t_type[1])) && p() && e('50'); // 返回2022年类型为holiday的holiday list
r($holiday->getListTest($t_numyear[0], $t_type[2])) && p() && e('50'); // 返回2022年类型为working的holiday list
r($holiday->getListTest($t_numyear[0], $t_type[3])) && p() && e('100'); // 返回2022年类型为空holiday list