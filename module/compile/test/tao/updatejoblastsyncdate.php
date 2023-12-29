#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel->updateJobLastSyncDate().
timeout=0
cid=1

- 将id为1的job的lastSyncDate更新为当前时间，检查是否更新成功 @1
- 将id为1的job的lastSyncDate更新为一天后的时间，检查是否更新成功 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/compile.class.php';

zdTable('job')->gen(1);
su('admin');

$compile = new compileTest();

$now = date('Y-m-d H:i:s');

$compile->updateJobLastSyncDate(1, $now);

$time = $tester->dao->select('lastSyncDate')->from(TABLE_JOB)->where('id')->eq(1)->fetch('lastSyncDate');
r($time === $now) && p('') && e(1);  //将id为1的job的lastSyncDate更新为当前时间，检查是否更新成功

$date = date('Y-m-d H:i:s', strtotime('+1 day'));
$compile->updateJobLastSyncDate(1, $date);

unset(dao::$cache[TABLE_JOB]);
$time = $tester->dao->select('lastSyncDate')->from(TABLE_JOB)->where('id')->eq(1)->fetch('lastSyncDate');
r($time === $date) && p('') && e(1);  //将id为1的job的lastSyncDate更新为一天后的时间，检查是否更新成功
