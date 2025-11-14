#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel->checkField();
timeout=0
cid=15654

- 查看需求表中的id字段是否存在 @1
- 查看需求表中的tester字段是否存在 @0
- 查看任务表中的name字段是否存在 @1
- 查看任务表中的title字段是否存在 @0
- 查看任务表中的空字段是否存在 @0

*/

global $tester;
$tester->loadModel('common');

$result1 = $tester->common->checkField('`zt_story`',  'id');
$result2 = $tester->common->checkField('`zt_story`', 'tester');
$result3 = $tester->common->checkField('`zt_task`',  'name');
$result4 = $tester->common->checkField('`zt_task`', 'title');
$result5 = $tester->common->checkField('`zt_task`', '');

r($result1) && p() && e('1'); // 查看需求表中的id字段是否存在
r($result2) && p() && e('0'); // 查看需求表中的tester字段是否存在
r($result3) && p() && e('1'); // 查看任务表中的name字段是否存在
r($result4) && p() && e('0'); // 查看任务表中的title字段是否存在
r($result5) && p() && e('0'); // 查看任务表中的空字段是否存在