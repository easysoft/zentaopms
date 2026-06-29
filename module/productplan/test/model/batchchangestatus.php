#!/usr/bin/env php
<?php

/**

title=productplanModel->batchChangeStatus();
timeout=0
cid=17619

- wait -> doing属性status @doing
- done -> doing属性status @doing
- 不传递关闭原因，打印错误信息属性closedReason[] @『关闭原因』不能为空。
- doing -> closed属性status @closed
- done -> closed属性status @closed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(10);
$plan = new productplanModelTest('admin');

$result = $plan->batchChangeStatus('doing');
r($result[1]) && p('status') && e('doing'); // wait -> doing
r($result[3]) && p('status') && e('doing'); // done -> doing

$result = $plan->batchChangeStatus('closed');
r($result) && p('closedReason[]') && e('『关闭原因』不能为空。');  // 不传递关闭原因，打印错误信息

$result = $plan->batchChangeStatus('closed', true);
r($result[6]) && p('status') && e('closed'); // doing -> closed
r($result[7]) && p('status') && e('closed'); // done -> closed