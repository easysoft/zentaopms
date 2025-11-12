#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildCreateView();
timeout=0
cid=0

- 测试1: 正常日期
 - 属性result @success
 - 属性title @1
 - 属性times @1
 - 属性time @1
 - 属性users @1
- 测试2: 空日期
 - 属性result @success
 - 属性title @1
 - 属性times @1
 - 属性time @1
 - 属性users @1
- 测试3: 未来日期
 - 属性result @success
 - 属性date @2025-12-31
- 测试4: 历史日期
 - 属性result @success
 - 属性date @2020-06-01
- 测试5: 时间戳格式
 - 属性result @success
 - 属性times @1
 - 属性users @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(5);

su('admin');

$todoTest = new todoTest();

r($todoTest->buildCreateViewTest('2024-01-15')) && p('result,title,times,time,users') && e('success,1,1,1,1'); // 测试1: 正常日期
r($todoTest->buildCreateViewTest('')) && p('result,title,times,time,users') && e('success,1,1,1,1'); // 测试2: 空日期
r($todoTest->buildCreateViewTest('2025-12-31')) && p('result,date') && e('success,2025-12-31'); // 测试3: 未来日期
r($todoTest->buildCreateViewTest('2020-06-01')) && p('result,date') && e('success,2020-06-01'); // 测试4: 历史日期
r($todoTest->buildCreateViewTest('1735689600')) && p('result,times,users') && e('success,1,1'); // 测试5: 时间戳格式