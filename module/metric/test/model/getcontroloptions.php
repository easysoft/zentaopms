#!/usr/bin/env php
<?php
/**
title=getControlOptions
cid=1
pid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

zdTable('user')->config('user', true)->gen(30);
zdTable('product')->config('product', true)->gen(10);
zdTable('project')->config('project', true)->gen(40);

r(count($metric->getControlOptions('user')))    && p('') && e('11'); // 测试user对象数
r(count($metric->getControlOptions('product'))) && p('') && e('5');  // 测试product对象数
r(count($metric->getControlOptions('project'))) && p('') && e('8'); // 测试project对象数
