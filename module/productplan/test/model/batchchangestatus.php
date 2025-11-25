#!/usr/bin/env php
<?php
/**

title=productpanModel->batchChangeStatus();
timeout=0
cid=17619

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplan.unittest.class.php';

zenData('productplan')->loadYaml('productplan')->gen(10);
$plan = new productPlan('admin');

$result = $plan->batchChangeStatus('doing');
r($result[3]) && p('status') && e('doing'); // wait -> doing
r($result[5]) && p('status') && e('doing'); // closed -> doing

$result = $plan->batchChangeStatus('closed');
r($result) && p('closedReason[]') && e('『关闭原因』不能为空。');  // 不传递关闭原因，打印错误信息

$result = $plan->batchChangeStatus('closed', true);
r($result[7]) && p('status') && e('closed'); // done -> closed
r($result[8]) && p('status') && e('closed'); // doing -> closed
