#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 reporeviewflowModel->getList();
timeout=0
cid=0

- 获取代码库1的审批流程
 - 第1条的name属性 @review_flow1
 - 第1条的status属性 @enable
- 获取代码库2的审批流程数量 @1
- 获取代码库3的审批流程第3条的desc属性 @desc3
- 获取代码库4的审批流程第4条的name属性 @review_flow4
*/

zenData('ops_repo')->loadYaml('ops_repo', false, 2)->gen(10);
zenData('ops_review_flow')->gen(10);

$flow = new reporeviewflowTest();
r($flow->getListTest(1))        && p('1:name,status') && e('review_flow1,enable'); // 获取代码库1的审批流程
r(count($flow->getListTest(2))) && p()         && e('1');                          // 获取代码库2的审批流程数量
r($flow->getListTest(3))        && p('3:desc') && e('desc3');                      // 获取代码库3的审批流程
r($flow->getListTest(4))        && p('4:name') && e('review_flow4');               // 获取代码库4的审批流程
