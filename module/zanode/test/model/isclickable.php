#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->isClickable().
cid=1

- 测试对象node状态 suspend hostType physics 的 resume 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 resume 按钮是否可点击 @1
- 测试对象node状态 shutoff hostType physics 的 resume 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType 空 的 resume 按钮是否可点击 @0
- 测试对象node状态 suspend hostType physics 的 start 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 start 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType physics 的 start 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType 空 的 start 按钮是否可点击 @1
- 测试对象node状态 suspend hostType physics 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType physics 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType 空 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 running hostType physics 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 running hostType 空 的 getvnc 按钮是否可点击 @1
- 测试对象node状态 launch hostType physics 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 launch hostType 空 的 getvnc 按钮是否可点击 @1
- 测试对象node状态 wait hostType physics 的 getvnc 按钮是否可点击 @0
- 测试对象node状态 wait hostType 空 的 getvnc 按钮是否可点击 @1
- 测试对象node状态 shutoff hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType 空 的 close 按钮是否可点击 @0
- 测试对象node状态 launch hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 launch hostType 空 的 close 按钮是否可点击 @1
- 测试对象node状态 wait hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 wait hostType 空 的 close 按钮是否可点击 @0
- 测试对象node状态 creating_img hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 creating_img hostType 空 的 close 按钮是否可点击 @0
- 测试对象node状态 creating_snap hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 creating_snap hostType 空 的 close 按钮是否可点击 @0
- 测试对象node状态 restoring hostType physics 的 close 按钮是否可点击 @0
- 测试对象node状态 restoring hostType 空 的 close 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 shutoff hostType 空 的 reboot 按钮是否可点击 @0
- 测试对象node状态 launch hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 launch hostType 空 的 reboot 按钮是否可点击 @1
- 测试对象node状态 wait hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 wait hostType 空 的 reboot 按钮是否可点击 @0
- 测试对象node状态 creating_img hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 creating_img hostType 空 的 reboot 按钮是否可点击 @0
- 测试对象node状态 creating_snap hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 creating_snap hostType 空 的 reboot 按钮是否可点击 @0
- 测试对象node状态 restoring hostType physics 的 reboot 按钮是否可点击 @0
- 测试对象node状态 restoring hostType 空 的 reboot 按钮是否可点击 @0
- 测试对象node状态 suspend hostType physics 的 suspend 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 suspend 按钮是否可点击 @0
- 测试对象node状态 running hostType physics 的 suspend 按钮是否可点击 @0
- 测试对象node状态 running hostType 空 的 suspend 按钮是否可点击 @1
- 测试对象node状态 suspend hostType physics 的 createsnapshot 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 createsnapshot 按钮是否可点击 @0
- 测试对象node状态 running hostType physics 的 createsnapshot 按钮是否可点击 @0
- 测试对象node状态 running hostType 空 的 createsnapshot 按钮是否可点击 @1
- 测试对象node状态 suspend hostType physics 的 createimage 按钮是否可点击 @0
- 测试对象node状态 suspend hostType 空 的 createimage 按钮是否可点击 @0
- 测试对象node状态 running hostType physics 的 createimage 按钮是否可点击 @0
- 测试对象node状态 running hostType 空 的 createimage 按钮是否可点击 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('user')->gen(10);

su('admin');

$zanode = new zanodeTest();

$status   = array('suspend', 'shutoff', 'running', 'launch', 'wait', 'creating_img', 'creating_snap', 'restoring');
$hostType = array('physics', '');
$action   = array('resume', 'start', 'getvnc', 'close', 'reboot', 'suspend', 'createsnapshot', 'createimage');

r($zanode->isClickableTest($action[0], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 resume 按钮是否可点击
r($zanode->isClickabletest($action[0], $status[0], $hostType[1])) && p() && e('1'); // 测试对象node状态 suspend hostType 空 的 resume 按钮是否可点击
r($zanode->isClickabletest($action[0], $status[1], $hostType[0])) && p() && e('0'); // 测试对象node状态 shutoff hostType physics 的 resume 按钮是否可点击
r($zanode->isClickabletest($action[0], $status[1], $hostType[1])) && p() && e('0'); // 测试对象node状态 shutoff hostType 空 的 resume 按钮是否可点击

r($zanode->isClickableTest($action[1], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 start 按钮是否可点击
r($zanode->isClickabletest($action[1], $status[0], $hostType[1])) && p() && e('0'); // 测试对象node状态 suspend hostType 空 的 start 按钮是否可点击
r($zanode->isClickabletest($action[1], $status[1], $hostType[0])) && p() && e('0'); // 测试对象node状态 shutoff hostType physics 的 start 按钮是否可点击
r($zanode->isClickabletest($action[1], $status[1], $hostType[1])) && p() && e('1'); // 测试对象node状态 shutoff hostType 空 的 start 按钮是否可点击

r($zanode->isClickableTest($action[2], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[0], $hostType[1])) && p() && e('0'); // 测试对象node状态 suspend hostType 空 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[1], $hostType[0])) && p() && e('0'); // 测试对象node状态 shutoff hostType physics 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[1], $hostType[1])) && p() && e('0'); // 测试对象node状态 shutoff hostType 空 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[2], $hostType[0])) && p() && e('0'); // 测试对象node状态 running hostType physics 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[2], $hostType[1])) && p() && e('1'); // 测试对象node状态 running hostType 空 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[3], $hostType[0])) && p() && e('0'); // 测试对象node状态 launch hostType physics 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[3], $hostType[1])) && p() && e('1'); // 测试对象node状态 launch hostType 空 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[4], $hostType[0])) && p() && e('0'); // 测试对象node状态 wait hostType physics 的 getvnc 按钮是否可点击
r($zanode->isClickabletest($action[2], $status[4], $hostType[1])) && p() && e('1'); // 测试对象node状态 wait hostType 空 的 getvnc 按钮是否可点击

r($zanode->isClickabletest($action[3], $status[1], $hostType[0])) && p() && e('0'); // 测试对象node状态 shutoff hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[1], $hostType[1])) && p() && e('0'); // 测试对象node状态 shutoff hostType 空 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[3], $hostType[0])) && p() && e('0'); // 测试对象node状态 launch hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[3], $hostType[1])) && p() && e('1'); // 测试对象node状态 launch hostType 空 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[4], $hostType[0])) && p() && e('0'); // 测试对象node状态 wait hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[4], $hostType[1])) && p() && e('0'); // 测试对象node状态 wait hostType 空 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[5], $hostType[0])) && p() && e('0'); // 测试对象node状态 creating_img hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[5], $hostType[1])) && p() && e('0'); // 测试对象node状态 creating_img hostType 空 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[6], $hostType[0])) && p() && e('0'); // 测试对象node状态 creating_snap hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[6], $hostType[1])) && p() && e('0'); // 测试对象node状态 creating_snap hostType 空 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[7], $hostType[0])) && p() && e('0'); // 测试对象node状态 restoring hostType physics 的 close 按钮是否可点击
r($zanode->isClickabletest($action[3], $status[7], $hostType[1])) && p() && e('0'); // 测试对象node状态 restoring hostType 空 的 close 按钮是否可点击

r($zanode->isClickabletest($action[4], $status[1], $hostType[0])) && p() && e('0'); // 测试对象node状态 shutoff hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[1], $hostType[1])) && p() && e('0'); // 测试对象node状态 shutoff hostType 空 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[3], $hostType[0])) && p() && e('0'); // 测试对象node状态 launch hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[3], $hostType[1])) && p() && e('1'); // 测试对象node状态 launch hostType 空 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[4], $hostType[0])) && p() && e('0'); // 测试对象node状态 wait hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[4], $hostType[1])) && p() && e('0'); // 测试对象node状态 wait hostType 空 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[5], $hostType[0])) && p() && e('0'); // 测试对象node状态 creating_img hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[5], $hostType[1])) && p() && e('0'); // 测试对象node状态 creating_img hostType 空 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[6], $hostType[0])) && p() && e('0'); // 测试对象node状态 creating_snap hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[6], $hostType[1])) && p() && e('0'); // 测试对象node状态 creating_snap hostType 空 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[7], $hostType[0])) && p() && e('0'); // 测试对象node状态 restoring hostType physics 的 reboot 按钮是否可点击
r($zanode->isClickabletest($action[4], $status[7], $hostType[1])) && p() && e('0'); // 测试对象node状态 restoring hostType 空 的 reboot 按钮是否可点击

r($zanode->isClickabletest($action[5], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 suspend 按钮是否可点击
r($zanode->isClickabletest($action[5], $status[0], $hostType[1])) && p() && e('0'); // 测试对象node状态 suspend hostType 空 的 suspend 按钮是否可点击
r($zanode->isClickabletest($action[5], $status[2], $hostType[0])) && p() && e('0'); // 测试对象node状态 running hostType physics 的 suspend 按钮是否可点击
r($zanode->isClickabletest($action[5], $status[2], $hostType[1])) && p() && e('1'); // 测试对象node状态 running hostType 空 的 suspend 按钮是否可点击

r($zanode->isClickabletest($action[6], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 createsnapshot 按钮是否可点击
r($zanode->isClickabletest($action[6], $status[0], $hostType[1])) && p() && e('0'); // 测试对象node状态 suspend hostType 空 的 createsnapshot 按钮是否可点击
r($zanode->isClickabletest($action[6], $status[2], $hostType[0])) && p() && e('0'); // 测试对象node状态 running hostType physics 的 createsnapshot 按钮是否可点击
r($zanode->isClickabletest($action[6], $status[2], $hostType[1])) && p() && e('1'); // 测试对象node状态 running hostType 空 的 createsnapshot 按钮是否可点击

r($zanode->isClickabletest($action[7], $status[0], $hostType[0])) && p() && e('0'); // 测试对象node状态 suspend hostType physics 的 createimage 按钮是否可点击
r($zanode->isClickabletest($action[7], $status[0], $hostType[1])) && p() && e('0'); // 测试对象node状态 suspend hostType 空 的 createimage 按钮是否可点击
r($zanode->isClickabletest($action[7], $status[2], $hostType[0])) && p() && e('0'); // 测试对象node状态 running hostType physics 的 createimage 按钮是否可点击
r($zanode->isClickabletest($action[7], $status[2], $hostType[1])) && p() && e('1'); // 测试对象node状态 running hostType 空 的 createimage 按钮是否可点击
