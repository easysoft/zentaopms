#!/usr/bin/env php
<?php
/**
title=getPairsByScope
timeout=0
cid=1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

zdTable('user')->config('user', true)->gen(30);
zdTable('product')->config('product', true)->gen(10);
zdTable('project')->config('project', true)->gen(40);

r(count($metric->getPairsByScope('user')))    && p('') && e('11'); // 测试user对象数
r(count($metric->getPairsByScope('product'))) && p('') && e('5');  // 测试product对象数
r(count($metric->getPairsByScope('project'))) && p('') && e('20'); // 测试project对象数

r($metric->getPairsByScope('user'))    && p('admin,user3,user6,user9') && e('admin,用户3,用户6,用户9');       // 测试部分user对象
r($metric->getPairsByScope('product')) && p('1,3,5')                   && e('正常产品1,正常产品3,正常产品5'); // 测试部分product对象
r($metric->getPairsByScope('project')) && p('11,13,15')                && e('项目11,项目13,项目15');          // 测试部分project对象
