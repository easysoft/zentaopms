#!/usr/bin/env php
<?php

/**

title=count_of_resolved_code_issues_in_codebase
timeout=0
cid=1

- 测试分组数。 @3
- 测试代码库已解决问题数。
 - 第0条的repo属性 @2
 - 第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->config('bug_repo', true, 4)->gen(10);
zdTable('repo')->config('repo', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))            && p('')             && e('3');   // 测试分组数。
r($calc->getResult(array('repo' => 2))) && p('0:repo,value') && e('2,1'); // 测试代码库已解决问题数。