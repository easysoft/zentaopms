#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 weeklyModel->getThisSunday();
cid=19734

- 查询日期为空 @1970-01-04
- 查询日期为星期日 @2022-05-08
- 查询日期为其他 @2022-05-01
- 查询日期为2022-03-01 @2022-03-06
- 查询日期为星期一 @2022-03-06

*/
$date = array('2022-05-08', '2022-04-29', '2022-03-01', '2022-02-28');

$weekly = new weeklyModelTest();

r($weekly->getThisSundayTest(''))       && p() && e('1970-01-04'); //查询日期为空
r($weekly->getThisSundayTest($date[0])) && p() && e('2022-05-08'); //查询日期为星期日
r($weekly->getThisSundayTest($date[1])) && p() && e('2022-05-01'); //查询日期为其他
r($weekly->getThisSundayTest($date[2])) && p() && e('2022-03-06'); //查询日期为2022-03-01
r($weekly->getThisSundayTest($date[3])) && p() && e('2022-03-06'); //查询日期为星期一