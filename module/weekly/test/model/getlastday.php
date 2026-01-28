#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('holiday')->gen(10);
su('admin');

/**

title=测试 weeklyModel->getLastDay();
timeout=0
cid=19723

- 查询日期为星期六 @2022-05-06
- 查询日期为星期日 @2022-05-06
- 查询日期为其他 @2022-04-29
- 查询日期为空 @1970-01-02
- 查询日期为空 @-0001-12-03

*/

$date = array('2022-05-07', '2022-05-08', '2022-04-29', '', '0000-00-00');

$weekly = new weeklyModelTest();
r($weekly->getLastDayTest($date[0])) && p() && e('2022-05-06');  // 查询日期为星期六
r($weekly->getLastDayTest($date[1])) && p() && e('2022-05-06');  // 查询日期为星期日
r($weekly->getLastDayTest($date[2])) && p() && e('2022-04-29');  // 查询日期为其他
r($weekly->getLastDayTest($date[3])) && p() && e('1970-01-02');  // 查询日期为空
r($weekly->getLastDayTest($date[4])) && p() && e('-0001-12-03'); // 查询日期为空
