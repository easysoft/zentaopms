#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->loadYaml('bug')->gen('100');
zenData('user')->gen(10);

su('admin');

/**

title=测试 reportModel->getUserBugs();
timeout=0
cid=18169

- 获取admin人员 bugID第0条的id属性 @1
- 获取user1人员 bugID第0条的id属性 @2
- 获取user2人员 bugID第0条的id属性 @3
- 获取admin人员 bug数 @29
- 获取user1人员 bug数 @27
- 获取user2人员 bug数 @28

*/

$report = new reportModelTest();
$result = $report->getUserBugsTest();
r($result['admin']) && p('0:id') && e('1');   // 获取admin人员 bugID
r($result['user1']) && p('0:id') && e('2');   // 获取user1人员 bugID
r($result['user2']) && p('0:id') && e('3');   // 获取user2人员 bugID
r(count($result['admin'])) && p() && e('29'); // 获取admin人员 bug数
r(count($result['user1'])) && p() && e('27'); // 获取user1人员 bug数
r(count($result['user2'])) && p() && e('28'); // 获取user2人员 bug数
