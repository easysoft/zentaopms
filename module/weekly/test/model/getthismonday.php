#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getThisMonday();
cid=19733

- 查询日期为空 @1969-12-29
- 查询日期为星期日 @2022-05-02
- 查询日期为其他 @2022-04-25
- 查询日期 2022-03-25 @2022-03-21
- 查询日期为星期一 @2022-03-21

*/
$date = array('2022-05-08', '2022-04-29', '2022-03-25', '2022-03-21');

$weekly = new weeklyTest();

r($weekly->getThisMondayTest(''))       && p() && e('1969-12-29'); //查询日期为空
r($weekly->getThisMondayTest($date[0])) && p() && e('2022-05-02'); //查询日期为星期日
r($weekly->getThisMondayTest($date[1])) && p() && e('2022-04-25'); //查询日期为其他
r($weekly->getThisMondayTest($date[2])) && p() && e('2022-03-21'); //查询日期 2022-03-25
r($weekly->getThisMondayTest($date[3])) && p() && e('2022-03-21'); //查询日期为星期一