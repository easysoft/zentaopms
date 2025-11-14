#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterBatchEdit();
timeout=0
cid=15471

- 执行$result1['result']) && $result1['result'] @1
- 执行$result2['result']) && $result2['result'] @1
- 执行$result3['result']) && $result3['result'] @1
- 执行$result4['result']) && $result4['result'] @1
- 执行$result5['result']) && $result5['result'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 设置session中的bugList用于跳转
$_SESSION['bugList'] = '/bug-browse-1.html';

// 测试1:空任务ID列表(正常批量编辑场景,无bug转任务)
$result1 = $bugTest->responseAfterBatchEditTest(array(), '');
r(isset($result1['result']) && $result1['result']) && p() && e('1');

// 测试2:空任务ID列表且自定义消息
$result2 = $bugTest->responseAfterBatchEditTest(array(), '批量编辑成功');
r(isset($result2['result']) && $result2['result']) && p() && e('1');

// 测试3:有单个任务ID(bug转为任务的场景)
$result3 = $bugTest->responseAfterBatchEditTest(array(101 => 1), '');
r(isset($result3['result']) && $result3['result']) && p() && e('1');

// 测试4:有多个任务ID(多个bug转为任务)
$result4 = $bugTest->responseAfterBatchEditTest(array(102 => 2, 103 => 3), '批量编辑成功');
r(isset($result4['result']) && $result4['result']) && p() && e('1');

// 测试5:有任务ID且自定义消息
$result5 = $bugTest->responseAfterBatchEditTest(array(104 => 4), '批量编辑并转任务成功');
r(isset($result5['result']) && $result5['result']) && p() && e('1');