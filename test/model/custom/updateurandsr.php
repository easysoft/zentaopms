#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->updateURAndSR();
cid=1
pid=1

测试修改key为1，SRName值更改为空 >> {"SRName":"\u8f6f\u4ef6\u9700\u6c42","URName":"\u7528\u6237\u9700\u6c42"}
测试修改key为1，SRName值更改为软件需求 >> {"SRName":"\u8f6f\u4ef6\u9700\u6c42","URName":"\u7528\u6237\u9700\u6c42"}
测试修改key为1，SRName值更改为软件需求1 >> {"SRName":"\u8f6f\u4ef6\u9700\u6c421","URName":"\u7528\u6237\u9700\u6c42"}
测试修改key为0，SRName值更改为空 >> 0
测试修改key为0，SRName值更改为软件需求 >> 0
测试修改key为0，SRName值更改为软件需求1 >> 0

*/
$key    = array(1, 0);
$SRName = array('', '软件需求', '软件需求1');

$custom = new customTest();

r($custom->updateURAndSRTest($key[0], $SRName[0])) && p('value') && e('{"SRName":"\u8f6f\u4ef6\u9700\u6c42","URName":"\u7528\u6237\u9700\u6c42"}');  //测试修改key为1，SRName值更改为空
r($custom->updateURAndSRTest($key[0], $SRName[1])) && p('value') && e('{"SRName":"\u8f6f\u4ef6\u9700\u6c42","URName":"\u7528\u6237\u9700\u6c42"}');  //测试修改key为1，SRName值更改为软件需求
r($custom->updateURAndSRTest($key[0], $SRName[2])) && p('value') && e('{"SRName":"\u8f6f\u4ef6\u9700\u6c421","URName":"\u7528\u6237\u9700\u6c42"}'); //测试修改key为1，SRName值更改为软件需求1
r($custom->updateURAndSRTest($key[1], $SRName[0])) && p()        && e('0');                                                                          //测试修改key为0，SRName值更改为空
r($custom->updateURAndSRTest($key[1], $SRName[1])) && p()        && e('0');                                                                          //测试修改key为0，SRName值更改为软件需求
r($custom->updateURAndSRTest($key[1], $SRName[2])) && p()        && e('0');                                                                          //测试修改key为0，SRName值更改为软件需求1

