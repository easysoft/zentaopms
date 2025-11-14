#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getWeekPairs();
timeout=0
cid=19736

- 执行weekly模块的getWeekPairsTest方法，参数是1, 1  @0
- 执行weekly模块的getWeekPairsTest方法，参数是1, 2  @0
- 执行weekly模块的getWeekPairsTest方法，参数是1, 3 属性20230501 @第 2 周( 2023-05-01 ~ 2023-05-07)
- 执行weekly模块的getWeekPairsTest方法，参数是2, 1 属性20230410 @第 1 周( 2023-04-10 ~ 2023-04-16)
- 执行weekly模块的getWeekPairsTest方法，参数是2, 2  @0
- 执行weekly模块的getWeekPairsTest方法，参数是2, 3 属性20230424 @第 3 周( 2023-04-24 ~ 2023-04-30)
- 执行weekly模块的getWeekPairsTest方法，参数是3, 1  @0
- 执行weekly模块的getWeekPairsTest方法，参数是3, 2  @0
- 执行weekly模块的getWeekPairsTest方法，参数是3, 3  @0

*/
$weekly = new weeklyTest();

r($weekly->getWeekPairsTest(1, 1)) && p() && e('0');
r($weekly->getWeekPairsTest(1, 2)) && p() && e('0');
r($weekly->getWeekPairsTest(1, 3)) && p('20230501') && e('第 2 周( 2023-05-01 ~ 2023-05-07)');
r($weekly->getWeekPairsTest(2, 1)) && p('20230410') && e('第 1 周( 2023-04-10 ~ 2023-04-16)');
r($weekly->getWeekPairsTest(2, 2)) && p() && e('0');
r($weekly->getWeekPairsTest(2, 3)) && p('20230424') && e('第 3 周( 2023-04-24 ~ 2023-04-30)');
r($weekly->getWeekPairsTest(3, 1)) && p() && e('0');
r($weekly->getWeekPairsTest(3, 2)) && p() && e('0');
r($weekly->getWeekPairsTest(3, 3)) && p() && e('0');
