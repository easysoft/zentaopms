#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('notify')->gen(3);

/**

title=测试 webhookModel->setSentStatus();
timeout=0
cid=1

- 获取第一条记录的status,sendTime
 - 第1条的status属性 @done
 - 第1条的sendTime属性 @2023-01-01 00:00:00
- 获取第二条记录的status,sendTime
 - 第2条的status属性 @done
 - 第2条的sendTime属性 @2023-01-01 00:00:00
- 获取第三条记录的status,sendTime
 - 第3条的status属性 @done
 - 第3条的sendTime属性 @2023-01-01 00:00:00
- 获取第一条记录的status,sendTime第1条的status属性 @fail
- 获取第二条记录的status,sendTime第2条的status属性 @fail
- 获取第三条记录的status,sendTime第3条的status属性 @fail

*/

$idList = array(1, 2, 3);
$webhook = new webhookTest();

$result = $webhook->setSentStatusTest($idList, 'done', '2023-01-01 00:00:00');

r($result) && p('1:status,sendTime') && e('done,2023-01-01 00:00:00'); // 获取第一条记录的status,sendTime
r($result) && p('2:status,sendTime') && e('done,2023-01-01 00:00:00'); // 获取第二条记录的status,sendTime
r($result) && p('3:status,sendTime') && e('done,2023-01-01 00:00:00'); // 获取第三条记录的status,sendTime

$result = $webhook->setSentStatusTest($idList, 'fail');

r($result) && p('1:status') && e('fail'); // 获取第一条记录的status,sendTime
r($result) && p('2:status') && e('fail'); // 获取第二条记录的status,sendTime
r($result) && p('3:status') && e('fail'); // 获取第三条记录的status,sendTime