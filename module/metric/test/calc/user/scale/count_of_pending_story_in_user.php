#!/usr/bin/env php
<?php

/**

title=count_of_pending_story_in_user
timeout=0
cid=1

- 测试分组数。 @6
- 测试用户dev。第0条的value属性 @32

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', true, 4)->gen(20);
zdTable('story')->config('story_projected', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('32'); // 测试用户dev。