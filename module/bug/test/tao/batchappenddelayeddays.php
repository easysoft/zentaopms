#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=bugModel->batchAppendDelayedDays();
cid=15413

- 检查产品1  中的bug延期天数属性delay @7,1,9
- 检查产品7  中的bug延期天数属性delay @11,5,13
- 检查产品18 中的bug延期天数属性delay @9,17,11
- 检查产品20 中的bug延期天数属性delay @1,9,3
- 检查产品28 中的bug延期天数属性delay @11,19,13
- 检查产品33 中的bug延期天数属性delay @19,13,7

*/

zenData('product')->gen(40);
zenData('user')->gen(10);
zenData('project')->gen(40);
zenData('bug')->loadYaml('bug_product')->gen(100);

su('admin');

$productIDList = array(1, 7, 18, 20, 28, 33);

$bug = new bugTaoTest();

r($bug->batchAppendDelayedDaysTest($productIDList[0])) && p('delay', '-') && e('7,1,9');    // 检查产品1  中的bug延期天数
r($bug->batchAppendDelayedDaysTest($productIDList[1])) && p('delay', '-') && e('11,5,13');  // 检查产品7  中的bug延期天数
r($bug->batchAppendDelayedDaysTest($productIDList[2])) && p('delay', '-') && e('9,17,11');  // 检查产品18 中的bug延期天数
r($bug->batchAppendDelayedDaysTest($productIDList[3])) && p('delay', '-') && e('1,9,3');    // 检查产品20 中的bug延期天数
r($bug->batchAppendDelayedDaysTest($productIDList[4])) && p('delay', '-') && e('11,19,13'); // 检查产品28 中的bug延期天数
r($bug->batchAppendDelayedDaysTest($productIDList[5])) && p('delay', '-') && e('19,13,7');  // 检查产品33 中的bug延期天数
