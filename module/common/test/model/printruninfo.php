#!/usr/bin/env php
<?php
/**

title=测试taskModel->printRunInfo();
timeout=0
cid=0

- 测试运行时间。属性timeUsed @10000
- 测试查询的sql次数。属性querys @40
- 测试运行时间。属性timeUsed @100000
- 测试查询的sql次数。属性querys @40
- 测试运行时间。属性timeUsed @1000000
- 测试查询的sql次数。属性querys @40

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$tester->loadModel('common');

ob_start();
$result1 = $tester->common->printRunInfo(getTime() - 10);
$result2 = $tester->common->printRunInfo(getTime() - 100);
$result3 = $tester->common->printRunInfo(getTime() - 1000);
ob_end_clean();
r($result1) && p('timeUsed') && e('10000');   // 测试运行时间。
r($result1) && p('querys') && e('40');        // 测试查询的sql次数。
r($result2) && p('timeUsed') && e('100000');  // 测试运行时间。
r($result2) && p('querys') && e('40');        // 测试查询的sql次数。
r($result3) && p('timeUsed') && e('1000000'); // 测试运行时间。
r($result3) && p('querys') && e('40');        // 测试查询的sql次数。
