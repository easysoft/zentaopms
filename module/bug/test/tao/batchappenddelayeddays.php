#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
zdTable('bug')->gen(100);
zdTable('product')->gen(40);
zdTable('user')->gen(10);
zdTable('project')->gen(40);

su('admin');

/**

title=bugModel->batchAppendDelayedDays();
cid=1
pid=1

*/

$productIDList = array(1, 7, 18, 20, 28, 33);

$bug = new bugTest();
r($bug->batchAppendDelayedDaysTest($productIDList[0])) && p('delay', '-') && e('38,36,34'); // 检查deadline在resolvedDate前 产品1 中的bug是否延期
r($bug->batchAppendDelayedDaysTest($productIDList[1])) && p('delay', '-') && e('2,0,0');    // 检查deadline在resolvedDate后 产品7 中的bug是否延期
r($bug->batchAppendDelayedDaysTest($productIDList[2])) && p('delay', '-') && e('16,14,12'); // 检查deadline在resolvedDate前 产品18 中的bug是否延期
r($bug->batchAppendDelayedDaysTest($productIDList[3])) && p('delay', '-') && e('4,2,0');    // 检查deadline在resolvedDate后 产品20 中的bug是否延期
r($bug->batchAppendDelayedDaysTest($productIDList[4])) && p('delay', '-') && e('15,13,11'); // 检查deadline在resolvedDate前 产品28 中的bug是否延期
r($bug->batchAppendDelayedDaysTest($productIDList[5])) && p('delay', '-') && e('6,4,2');    // 检查deadline在resolvedDate后 产品33 中的bug是否延期
