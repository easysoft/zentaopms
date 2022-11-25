#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfResolvedBugsPerDay();
cid=1
pid=1

获取解决的bug0 >> 14
获取解决的bug1 >> 14
获取解决的bug2 >> 14
获取解决的bug3 >> 14
获取解决的bug4 >> 14
获取解决的bug5 >> 14
获取解决的bug6 >> 14
获取解决的bug7 >> 14
获取解决的bug8 >> 14
获取解决的bug9 >> 14

*/

$bug=new bugTest();
r($bug->getDataOfResolvedBugsPerDayTest()) && p('0:value') && e('14'); // 获取解决的bug0
r($bug->getDataOfResolvedBugsPerDayTest()) && p('1:value') && e('14'); // 获取解决的bug1
r($bug->getDataOfResolvedBugsPerDayTest()) && p('2:value') && e('14'); // 获取解决的bug2
r($bug->getDataOfResolvedBugsPerDayTest()) && p('3:value') && e('14'); // 获取解决的bug3
r($bug->getDataOfResolvedBugsPerDayTest()) && p('4:value') && e('14'); // 获取解决的bug4
r($bug->getDataOfResolvedBugsPerDayTest()) && p('5:value') && e('14'); // 获取解决的bug5
r($bug->getDataOfResolvedBugsPerDayTest()) && p('6:value') && e('14'); // 获取解决的bug6
r($bug->getDataOfResolvedBugsPerDayTest()) && p('7:value') && e('14'); // 获取解决的bug7
r($bug->getDataOfResolvedBugsPerDayTest()) && p('8:value') && e('14'); // 获取解决的bug8
r($bug->getDataOfResolvedBugsPerDayTest()) && p('9:value') && e('14'); // 获取解决的bug9