#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->saveLogs();
cid=1

- 测试记录日志 log 1，然后获取最后一条日志 @log 1
- 测试记录日志 log 2，然后获取最后一条日志 @log 2
- 测试记录日志 log 3，然后获取最后一条日志 @log 3
- 测试记录日志 log 4，然后获取最后一条日志 @log 4
- 测试记录日志 空，然后获取最后一条日志 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$logs = array('log 1', 'log 2', 'log 3', 'log 4', '');

$upgrade = new upgradeTest();
r($upgrade->saveLogsTest($logs[0])) && p() && e('log 1'); // 测试记录日志 log 1，然后获取最后一条日志
r($upgrade->saveLogsTest($logs[1])) && p() && e('log 2'); // 测试记录日志 log 2，然后获取最后一条日志
r($upgrade->saveLogsTest($logs[2])) && p() && e('log 3'); // 测试记录日志 log 3，然后获取最后一条日志
r($upgrade->saveLogsTest($logs[3])) && p() && e('log 4'); // 测试记录日志 log 4，然后获取最后一条日志
r($upgrade->saveLogsTest($logs[4])) && p() && e('0');      // 测试记录日志 空，然后获取最后一条日志
