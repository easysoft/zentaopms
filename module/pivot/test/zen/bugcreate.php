#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::bugCreate();
timeout=0
cid=0

- 测试不传参数的默认情况 >> 查询上月至今日的Bug数据
- 测试指定时间范围查询Bug数据 >> 返回指定时间范围的Bug数据
- 测试指定产品过滤Bug数据 >> 返回指定产品的Bug数据
- 测试指定执行过滤Bug数据 >> 返回指定执行的Bug数据
- 测试同时指定产品和执行过滤Bug数据 >> 返回同时满足产品和执行条件的Bug数据

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

zenData('bug')->loadYaml('bugcreate', false, 2)->gen(20);
zenData('product')->loadYaml('bugcreate', false, 2)->gen(5);
zenData('project')->loadYaml('bugcreate', false, 2)->gen(5);
zenData('user')->loadYaml('bugcreate', false, 2)->gen(10);

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->bugCreateTest('', '', 0, 0)) && p('begin,end') && e('2025-10-01,2025-11-10');
r($pivotTest->bugCreateTest('2024-10-01', '2024-10-31', 0, 0)) && p('begin,end') && e('2024-10-01,2024-10-31');
r($pivotTest->bugCreateTest('2024-10-01', '2024-10-31', 1, 0)) && p('begin,end,product') && e('2024-10-01,2024-10-31,1');
r($pivotTest->bugCreateTest('2024-10-01', '2024-10-31', 0, 101)) && p('begin,end,execution') && e('2024-10-01,2024-10-31,101');
r($pivotTest->bugCreateTest('2024-10-01', '2024-10-31', 1, 101)) && p('begin,end,product,execution') && e('2024-10-01,2024-10-31,1,101');
