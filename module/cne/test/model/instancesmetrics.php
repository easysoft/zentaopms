#!/usr/bin/env php
<?php

/**

title=测试 cneModel->instancesMetrics();
timeout=0
cid=1

- 第一个实例
 - 属性name @gitlab-20231226133115
 - 第cpu条的limit属性 @6
 - 第memory条的limit属性 @6442450944
- 第二个实例
 - 属性name @subversion-20240102093246
 - 第cpu条的limit属性 @0.5
 - 第memory条的limit属性 @268435456

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel  = new cneTest();

$result = $cneModel->instancesMetricsTest();
r($result[1]) && p('name;cpu:limit;memory:limit') && e('gitlab-20231226133115,6,6442450944'); // 第一个实例
r($result[2]) && p('name;cpu:limit;memory:limit') && e('subversion-20240102093246,0.5,268435456'); // 第二个实例