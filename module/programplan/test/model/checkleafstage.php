#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';

function initData()
{
    zdTable('user')->gen(5);
    zdTable('project')->config('checkleafstage')->gen(5);
}

su('admin');


/**

title=测试programplanModel->checkLeafStage();
cid=1
pid=1

- 测试id为2判断是否为叶子节点 @0

- 测试id为5判断是否为叶子节点 @1

*/

$plan = new programplanTest();

r($plan->checkLeafStageTest(2)) && p('') && e(0); // 测试id为2判断是否为叶子节点
r($plan->checkLeafStageTest(5)) && p('') && e(1); // 测试id为5判断是否为叶子节点
