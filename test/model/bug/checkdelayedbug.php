#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->checkDelayBug();
cid=1
pid=1

检查deadline在1天前 resolvedDate不存在 状态为未解决的bug是否延期 >> 1
检查deadline在1天前 resolvedDate不存在 状态为已解决的bug是否延期 >> 1
检查deadline在1天前 resolvedDate不存在 状态为已关闭的bug是否延期 >> 1
检查deadline在3天前 resolvedDate在1天前 状态为未解决的bug是否延期 >> 2
检查deadline在3天前 resolvedDate在1天前 状态为已解决的bug是否延期 >> 2
检查deadline在3天前 resolvedDate在1天前 状态为已关闭的bug是否延期 >> 2
检查deadline在3天前 resolvedDate在4天前 状态为未解决的bug是否延期 >> 0
检查deadline在3天前 resolvedDate在4天前 状态为已解决的bug是否延期 >> 0
检查deadline在3天前 resolvedDate在4天前 状态为已关闭的bug是否延期 >> 0
检查deadline在3天后 resolvedDate在4天后 状态为未解决的bug是否延期 >> 1
检查deadline在3天后 resolvedDate在4天后 状态为已解决的bug是否延期 >> 1
检查deadline在3天后 resolvedDate在4天后 状态为已关闭的bug是否延期 >> 1
检查deadline在3天后 resolvedDate在4天前 状态为未解决的bug是否延期 >> 0
检查deadline在3天后 resolvedDate在4天前 状态为已解决的bug是否延期 >> 0
检查deadline在3天后 resolvedDate在4天前 状态为已关闭的bug是否延期 >> 0

*/

$bug1 = new stdclass();
$bug1->deadline     = '-1';
$bug1->resolvedDate = '0';

$bug2 = new stdclass();
$bug2->deadline     = '-3';
$bug2->resolvedDate = '-1';

$bug3 = new stdclass();
$bug3->deadline     = '-3';
$bug3->resolvedDate = '-4';

$bug4 = new stdclass();
$bug4->deadline     = '3';
$bug4->resolvedDate = '4';

$bug5 = new stdclass();
$bug5->deadline     = '3';
$bug5->resolvedDate = '-4';

$statusList = array('active', 'resolved', 'closed');

$bug = new bugTest();
r($bug->checkDelayBugTest($bug1, $statusList[0])) && p('delay') && e('1'); // 检查deadline在1天前 resolvedDate不存在 状态为未解决的bug是否延期
r($bug->checkDelayBugTest($bug1, $statusList[1])) && p('delay') && e('1'); // 检查deadline在1天前 resolvedDate不存在 状态为已解决的bug是否延期
r($bug->checkDelayBugTest($bug1, $statusList[2])) && p('delay') && e('1'); // 检查deadline在1天前 resolvedDate不存在 状态为已关闭的bug是否延期
r($bug->checkDelayBugTest($bug2, $statusList[0])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为未解决的bug是否延期
r($bug->checkDelayBugTest($bug2, $statusList[1])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为已解决的bug是否延期
r($bug->checkDelayBugTest($bug2, $statusList[2])) && p('delay') && e('2'); // 检查deadline在3天前 resolvedDate在1天前 状态为已关闭的bug是否延期
r($bug->checkDelayBugTest($bug3, $statusList[0])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为未解决的bug是否延期
r($bug->checkDelayBugTest($bug3, $statusList[1])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为已解决的bug是否延期
r($bug->checkDelayBugTest($bug3, $statusList[2])) && p('delay') && e('0'); // 检查deadline在3天前 resolvedDate在4天前 状态为已关闭的bug是否延期
r($bug->checkDelayBugTest($bug4, $statusList[0])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为未解决的bug是否延期
r($bug->checkDelayBugTest($bug4, $statusList[1])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为已解决的bug是否延期
r($bug->checkDelayBugTest($bug4, $statusList[2])) && p('delay') && e('1'); // 检查deadline在3天后 resolvedDate在4天后 状态为已关闭的bug是否延期
r($bug->checkDelayBugTest($bug5, $statusList[0])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为未解决的bug是否延期
r($bug->checkDelayBugTest($bug5, $statusList[1])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为已解决的bug是否延期
r($bug->checkDelayBugTest($bug5, $statusList[2])) && p('delay') && e('0'); // 检查deadline在3天后 resolvedDate在4天前 状态为已关闭的bug是否延期