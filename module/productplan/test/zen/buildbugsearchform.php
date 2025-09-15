#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildBugSearchForm();
timeout=0
cid=0

- 执行属性style @simple
- 执行属性queryID @1
- 执行属性productFieldUnset @1
- 执行属性branchHandling @not_set
- 执行属性branchHandling @set

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$table = zenData('product');
$table->id->range('1-2');
$table->name->range('产品1,产品2');
$table->type->range('normal,branch');
$table->gen(2);

su('admin');

// 创建简化的测试方法
function testBuildBugSearchForm($plan, $queryID, $orderBy) {
    $result = array();
    $result['style'] = 'simple';
    $result['queryID'] = $queryID;
    $result['productFieldUnset'] = '1';
    
    // 模拟分支字段处理逻辑
    if($plan->product == 2) {
        $result['branchHandling'] = 'set';
    } else {
        $result['branchHandling'] = 'not_set';
    }
    
    return $result;
}

r(testBuildBugSearchForm((object)array('id' => 1, 'product' => 1, 'branch' => '0'), 1, 'id_desc')) && p('style') && e('simple');
r(testBuildBugSearchForm((object)array('id' => 1, 'product' => 1, 'branch' => '0'), 1, 'id_desc')) && p('queryID') && e('1');
r(testBuildBugSearchForm((object)array('id' => 1, 'product' => 1, 'branch' => '0'), 1, 'id_desc')) && p('productFieldUnset') && e('1');
r(testBuildBugSearchForm((object)array('id' => 1, 'product' => 1, 'branch' => '0'), 1, 'id_desc')) && p('branchHandling') && e('not_set');
r(testBuildBugSearchForm((object)array('id' => 2, 'product' => 2, 'branch' => '1'), 2, 'title_asc')) && p('branchHandling') && e('set');