#!/usr/bin/env php
<?php
/**

title=count_of_assigned_bug_in_user
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('user' => 'admin')))    && p('0:value') && e('6');  // 测试用户admin
r($calc->getResult(array('user' => 'user')))     && p('0:value') && e('12'); // 测试用户user
r($calc->getResult(array('user' => 'test')))     && p('0:value') && e('30'); // 测试用户test
r($calc->getResult(array('user' => 'dev')))      && p('0:value') && e('48'); // 测试用户dev
r($calc->getResult(array('user' => 'pm')))       && p('0:value') && e('42'); // 测试用户pm
r($calc->getResult(array('user' => 'po')))       && p('0:value') && e('6');  // 测试用户po
r($calc->getResult(array('user' => 'notexist'))) && p('0:value') && e('0');  // 测试不存在的用户
