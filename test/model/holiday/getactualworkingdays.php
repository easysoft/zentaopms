#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/holiday.class.php';
su('admin');

/**

title=测试 holidayModel->getActualWorkingDays();
cid=1
pid=1

查询2022-04-01到2022-04-10的实际工作日 >> 8
查询2022-04-06到2022-04-12的实际工作日 >> 7
查询2022-05-10到2022-05-10的实际工作日 >> 9
查询2022-05-10到2022-05-10的实际工作日 >> 1
查询2022-05-13到2022-05-13的实际工作日 >> 1
查询2022-05-14到2022-05-14的实际工作日 >> 0
测试传入0000-00-00的情况 >> 0

*/

$holiday = new holidayTest();

r($holiday->getActualWorkingDaysTest('2022-04-01', '2022-04-10')) && p() && e('8'); //查询2022-04-01到2022-04-10的实际工作日
r($holiday->getActualWorkingDaysTest('2022-04-06', '2022-04-12')) && p() && e('7'); //查询2022-04-06到2022-04-12的实际工作日
r($holiday->getActualWorkingDaysTest('2022-05-10', '2022-05-20')) && p() && e('9'); //查询2022-05-10到2022-05-10的实际工作日
r($holiday->getActualWorkingDaysTest('2022-04-06', '2022-04-06')) && p() && e('1'); //查询2022-05-10到2022-05-10的实际工作日
r($holiday->getActualWorkingDaysTest('2022-05-13', '2022-05-13')) && p() && e('1'); //查询2022-05-13到2022-05-13的实际工作日
r($holiday->getActualWorkingDaysTest('2022-05-14', '2022-05-14')) && p() && e('0'); //查询2022-05-14到2022-05-14的实际工作日
r($holiday->getActualWorkingDaysTest('0000-00-00', '0000-00-00')) && p() && e('0'); //测试传入0000-00-00的情况