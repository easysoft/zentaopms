#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByStatus();
cid=1
pid=1

查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug >> bug8,BUG1
查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug >> bug8,BUG1
查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug >> 0
查询产品1 3 不存在的产品10001 与模块1821状态为unclosed下未确认的bug >> BUG1
查询产品1 3 不存在的产品10001 与模块1821状态为unresolved下未确认的bug >> BUG1
查询产品1 3 不存在的产品10001 与模块1821状态为toclosed下未确认的bug >> 0
查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为unclosed下未确认的bug >> 0
查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为unresolved下未确认的bug >> 0
查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为toclosed下未确认的bug >> 0
查询产品1 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug >> BUG1
查询产品1 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug >> BUG1
查询产品1 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug >> 0
查询产品1 与模块1821状态为unclosed下未确认的bug >> BUG1
查询产品1 与模块1821状态为unresolved下未确认的bug >> BUG1
查询产品1 与模块1821状态为toclosed下未确认的bug >> 0
查询产品1 与不存在的模块1000001 状态为unclosed下未确认的bug >> 0
查询产品1 与不存在的模块1000001 状态为unresolved下未确认的bug >> 0
查询产品1 与不存在的模块1000001 状态为toclosed下未确认的bug >> 0
查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug >> 0
查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug >> 0
查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug >> 0
查询不存在的产品10001 与模块1821状态为unclosed下未确认的bug >> 0
查询不存在的产品10001 与模块1821状态为unresolved下未确认的bug >> 0
查询不存在的产品10001 与模块1821状态为toclosed下未确认的bug >> 0
查询不存在的产品10001 与不存在的模块1000001 状态为unclosed下未确认的bug >> 0
查询不存在的产品10001 与不存在的模块1000001 状态为unresolved下未确认的bug >> 0
查询不存在的产品10001 与不存在的模块1000001 状态为toclosed下未确认的bug >> 0

*/

$productIDList = array('1,3,1000001', '1', '1000001');
$moduleIDList  = array('1821,1832,1000001', '1821', '1000001', '0');
$statusList    = array('unclosed', 'unresolved', 'toclosed');

$bug=new bugTest();

r($bug->getByStatusTest($productIDList[0], $moduleIDList[0], $statusList[0])) && p('title') && e('bug8,BUG1'); // 查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[0], $statusList[1])) && p('title') && e('bug8,BUG1'); // 查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[0], $statusList[2])) && p('title') && e('0');         // 查询产品1 3 不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[1], $statusList[0])) && p('title') && e('BUG1');      // 查询产品1 3 不存在的产品10001 与模块1821状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[1], $statusList[1])) && p('title') && e('BUG1');      // 查询产品1 3 不存在的产品10001 与模块1821状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[1], $statusList[2])) && p('title') && e('0');         // 查询产品1 3 不存在的产品10001 与模块1821状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[2], $statusList[0])) && p('title') && e('0');         // 查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[2], $statusList[1])) && p('title') && e('0');         // 查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[0], $moduleIDList[2], $statusList[2])) && p('title') && e('0');         // 查询产品1 3 不存在的产品10001 与不存在的模块1000001 状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[0], $statusList[0])) && p('title') && e('BUG1');      // 查询产品1 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[0], $statusList[1])) && p('title') && e('BUG1');      // 查询产品1 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[0], $statusList[2])) && p('title') && e('0');         // 查询产品1 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[1], $statusList[0])) && p('title') && e('BUG1');      // 查询产品1 与模块1821状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[1], $statusList[1])) && p('title') && e('BUG1');      // 查询产品1 与模块1821状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[1], $statusList[2])) && p('title') && e('0');         // 查询产品1 与模块1821状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[2], $statusList[0])) && p('title') && e('0');         // 查询产品1 与不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[2], $statusList[1])) && p('title') && e('0');         // 查询产品1 与不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[1], $moduleIDList[2], $statusList[2])) && p('title') && e('0');         // 查询产品1 与不存在的模块1000001 状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[0], $statusList[0])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[0], $statusList[1])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[0], $statusList[2])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821, 1832 不存在的模块1000001 状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[1], $statusList[0])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[1], $statusList[1])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[1], $statusList[2])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821状态为toclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[2], $statusList[0])) && p('title') && e('0');         // 查询不存在的产品10001 与不存在的模块1000001 状态为unclosed下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[2], $statusList[1])) && p('title') && e('0');         // 查询不存在的产品10001 与不存在的模块1000001 状态为unresolved下未确认的bug
r($bug->getByStatusTest($productIDList[2], $moduleIDList[2], $statusList[2])) && p('title') && e('0');         // 查询不存在的产品10001 与不存在的模块1000001 状态为toclosed下未确认的bug