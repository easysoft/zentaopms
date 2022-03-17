#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->checkDelayedBugs();
cid=1
pid=1

检查deadline在resolvedDate前 状态为未解决的bug是否延期 >> 35,33,31
检查deadline在resolvedDate后 状态为未解决的bug是否延期 >> 0,0,0
检查deadline在resolvedDate前 状态为已解决的bug是否延期 >> 10,8,6
检查deadline在resolvedDate后 状态为已解决的bug是否延期 >> 0,0,0
检查deadline在resolvedDate前 状态为已关闭的bug是否延期 >> 6,4,2
检查deadline在resolvedDate后 状态为已关闭的bug是否延期 >> 0,0,0



*/

$productIDList = array('1', '7','18', '20', '28', '33');

$bug = new bugTest();
r($bug->checkDelayedBugsTest($productIDList[0])) && p('delay') && e('35,33,31'); // 检查deadline在resolvedDate前 状态为未解决的bug是否延期
r($bug->checkDelayedBugsTest($productIDList[1])) && p('delay') && e('0,0,0');    // 检查deadline在resolvedDate后 状态为未解决的bug是否延期
r($bug->checkDelayedBugsTest($productIDList[2])) && p('delay') && e('10,8,6');   // 检查deadline在resolvedDate前 状态为已解决的bug是否延期
r($bug->checkDelayedBugsTest($productIDList[3])) && p('delay') && e('0,0,0');    // 检查deadline在resolvedDate后 状态为已解决的bug是否延期
r($bug->checkDelayedBugsTest($productIDList[4])) && p('delay') && e('6,4,2');    // 检查deadline在resolvedDate前 状态为已关闭的bug是否延期
r($bug->checkDelayedBugsTest($productIDList[5])) && p('delay') && e('0,0,0');    // 检查deadline在resolvedDate后 状态为已关闭的bug是否延期
