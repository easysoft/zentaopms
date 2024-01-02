#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getAppLogs();
timeout=0
cid=1

- 获取日志条目数 @32
- 获取第一条和最后一条日志
 - 第0条的content属性 @+ CONFIG_DIR=/data/config
 - 第31条的content属性 @[Tue Jan 02 14:50:46.867606 2024] [core:notice] [pid 247] AH00094: Command line: '/usr/sbin/httpd -D FOREGROUND'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();

$logs = $cneModel->getAppLogsTest();
r(count($logs->data)) && p() && e('32'); // 获取日志条目数
r($logs->data) && p('0:content;31:content') && e("+ CONFIG_DIR=/data/config,[Tue Jan 02 14:50:46.867606 2024] [core:notice] [pid 247] AH00094: Command line: '/usr/sbin/httpd -D FOREGROUND'"); // 获取第一条和最后一条日志