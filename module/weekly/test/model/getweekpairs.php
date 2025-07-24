#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';
su('admin');

/**

title=测试 weeklyModel->getWeekPairs();
timeout=0
cid=1

- 执行weekly模块的getWeekPairsTest方法，参数是$begin[0], $end[0]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[0], $end[1]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[0], $end[2] 属性20230501 @第 2 周( 2023-05-01 ~ 2023-05-07)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[0], $end[3] 属性20230424 @第 1 周( 2023-04-24 ~ 2023-04-30)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[1], $end[0] 属性20230410 @第 1 周( 2023-04-10 ~ 2023-04-16)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[1], $end[1]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[1], $end[2] 属性20230424 @第 3 周( 2023-04-24 ~ 2023-04-30)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[1], $end[3] 属性20230410 @第 1 周( 2023-04-10 ~ 2023-04-16)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[2], $end[0]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[2], $end[1]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[2], $end[2]  @0
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[2], $end[3] 属性20230501 @第 1 周( 2023-05-01 ~ 2023-05-07)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[3], $end[0] 属性19691229 @第 1 周( 1969-12-29 ~ 1970-01-04)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[3], $end[1] 属性19691229 @第 1 周( 1969-12-29 ~ 1970-01-04)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[3], $end[2] 属性20261123 @第 71 周( 2026-11-23 ~ 2026-11-29)
- 执行weekly模块的getWeekPairsTest方法，参数是$begin[3], $end[3] 属性20250804 @第 3 周( 2025-08-04 ~ 2025-08-10)

*/
$begin = array(1, 2, 3, '');
$end   = array(1, 2, 3, '');

$weekly = new weeklyTest();

r($weekly->getWeekPairsTest($begin[0], $end[0])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[0], $end[1])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[0], $end[2])) && p('20230501') && e('第 2 周( 2023-05-01 ~ 2023-05-07)');
r($weekly->getWeekPairsTest($begin[0], $end[3])) && p('20230424') && e('第 1 周( 2023-04-24 ~ 2023-04-30)');
r($weekly->getWeekPairsTest($begin[1], $end[0])) && p('20230410') && e('第 1 周( 2023-04-10 ~ 2023-04-16)');
r($weekly->getWeekPairsTest($begin[1], $end[1])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[1], $end[2])) && p('20230424') && e('第 3 周( 2023-04-24 ~ 2023-04-30)');
r($weekly->getWeekPairsTest($begin[1], $end[3])) && p('20230410') && e('第 1 周( 2023-04-10 ~ 2023-04-16)');
r($weekly->getWeekPairsTest($begin[2], $end[0])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[1])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[2])) && p() && e('0');
r($weekly->getWeekPairsTest($begin[2], $end[3])) && p('20230501') && e('第 1 周( 2023-05-01 ~ 2023-05-07)');
r($weekly->getWeekPairsTest($begin[3], $end[0])) && p('19691229') && e('第 1 周( 1969-12-29 ~ 1970-01-04)');
r($weekly->getWeekPairsTest($begin[3], $end[1])) && p('19691229') && e('第 1 周( 1969-12-29 ~ 1970-01-04)');
r($weekly->getWeekPairsTest($begin[3], $end[2])) && p('20261123') && e('第 71 周( 2026-11-23 ~ 2026-11-29)');
r($weekly->getWeekPairsTest($begin[3], $end[3])) && p('20250804') && e('第 3 周( 2025-08-04 ~ 2025-08-10)');
