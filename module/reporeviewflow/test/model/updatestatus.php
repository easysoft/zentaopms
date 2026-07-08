#!/usr/bin/env php
<?php

/**

title=测试 reporeviewflowModel::updateStatus();
timeout=0
cid=0

- 修改状态为enable
 - 属性status @enable
 - 属性name @review_flow1
- 修改状态为disable
 - 属性status @disable
 - 属性id @1
- 不存在的规则ID @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('ops_review_flow')->gen(1);

$flow = new reporeviewflowTest();

r($flow->updateStatusTest(1, 'enable'))  && p('status,name') && e('enable,review_flow1'); // 修改状态为enable
r($flow->updateStatusTest(1, 'disable')) && p('status,id')   && e('disable,1');           // 修改状态为disable
r($flow->updateStatusTest(0, 'enable'))  && p()              && e('0');                   // 不存在的规则ID
