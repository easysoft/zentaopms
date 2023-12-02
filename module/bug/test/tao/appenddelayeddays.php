#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('user')->gen(1);

su('admin');

/**

title=bugModel->appendDelayedDays();
timeout=0
cid=1

- 检查deadline在1天前 resolvedDate不存在 状态为未解决的bug是否延期属性delay @1
- 检查deadline在1天前 resolvedDate不存在 状态为已解决的bug是否延期属性delay @0
- 检查deadline在1天前 resolvedDate不存在 状态为已关闭的bug是否延期属性delay @0
- 检查deadline在3天前 resolvedDate在1天前 状态为未解决的bug是否延期属性delay @2
- 检查deadline在3天前 resolvedDate在1天前 状态为已解决的bug是否延期属性delay @2
- 检查deadline在3天前 resolvedDate在1天前 状态为已关闭的bug是否延期属性delay @2
- 检查deadline在3天前 resolvedDate在4天前 状态为未解决的bug是否延期属性delay @0
- 检查deadline在3天前 resolvedDate在4天前 状态为已解决的bug是否延期属性delay @0
- 检查deadline在3天前 resolvedDate在4天前 状态为已关闭的bug是否延期属性delay @0
- 检查deadline在3天后 resolvedDate在4天后 状态为未解决的bug是否延期属性delay @1
- 检查deadline在3天后 resolvedDate在4天后 状态为已解决的bug是否延期属性delay @1
- 检查deadline在3天后 resolvedDate在4天后 状态为已关闭的bug是否延期属性delay @1
- 检查deadline在3天后 resolvedDate在4天前 状态为未解决的bug是否延期属性delay @0
- 检查deadline在3天后 resolvedDate在4天前 状态为已解决的bug是否延期属性delay @0
- 检查deadline在3天后 resolvedDate在4天前 状态为已关闭的bug是否延期属性delay @0

*/

$deadline     = array('-1', '-3', '-3', '3', '3');
$resolvedDate = array('0', '-1', '-4', '4', '-4');

$statusList = array('active', 'resolved', 'closed');

$bug = new bugTest();
r($bug->appendDelayedDaysTest($deadline[0], $resolvedDate[0], $statusList[0])) && p('delay') && e('1'); // 检查deadline在1天前 resolvedDate不存在 状态为未解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[0], $resolvedDate[0], $statusList[1])) && p('delay') && e('0'); // 检查deadline在1天前 resolvedDate不存在 状态为已解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[0], $resolvedDate[0], $statusList[2])) && p('delay') && e('0'); // 检查deadline在1天前 resolvedDate不存在 状态为已关闭的bug是否延期
r($bug->appendDelayedDaysTest($deadline[1], $resolvedDate[1], $statusList[0])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为未解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[1], $resolvedDate[1], $statusList[1])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为已解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[1], $resolvedDate[1], $statusList[2])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为已关闭的bug是否延期
r($bug->appendDelayedDaysTest($deadline[2], $resolvedDate[2], $statusList[0])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为未解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[2], $resolvedDate[2], $statusList[1])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为已解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[2], $resolvedDate[2], $statusList[2])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为已关闭的bug是否延期
r($bug->appendDelayedDaysTest($deadline[3], $resolvedDate[3], $statusList[0])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为未解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[3], $resolvedDate[3], $statusList[1])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为已解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[3], $resolvedDate[3], $statusList[2])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为已关闭的bug是否延期
r($bug->appendDelayedDaysTest($deadline[4], $resolvedDate[4], $statusList[0])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为未解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[4], $resolvedDate[4], $statusList[1])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为已解决的bug是否延期
r($bug->appendDelayedDaysTest($deadline[4], $resolvedDate[4], $statusList[2])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为已关闭的bug是否延期
