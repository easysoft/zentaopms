#!/usr/bin/env php
<?php

/**

title=测试 cronModel->updateTime();
timeout=0
cid=1

- 更新调度器1的最新时间 @1
- 更新调度器2的最新时间 @2
- 更新消费者3的最新时间 @3
- 更新消费者4的最新时间 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cron.class.php';
su('admin');

$cron = new cronTest();

r($cron->updateTimeTest('scheduler', 1)) && p() && e(1); // 更新调度器1的最新时间
r($cron->updateTimeTest('scheduler', 2)) && p() && e(2); // 更新调度器2的最新时间
r($cron->updateTimeTest('consumer',  3)) && p() && e(3); // 更新消费者3的最新时间
r($cron->updateTimeTest('consumer',  4)) && p() && e(4); // 更新消费者4的最新时间