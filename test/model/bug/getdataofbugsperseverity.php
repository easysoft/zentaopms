#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerSeverity();
cid=1
pid=1

获取严重程度1数据 >> 1,79
获取严重程度2数据 >> 2,79
获取严重程度3数据 >> 3,79
获取严重程度4数据 >> 4,78

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerSeverityTest()) && p('1:name,value') && e('1,79');   // 获取严重程度1数据
r($bug->getDataOfBugsPerSeverityTest()) && p('2:name,value') && e('2,79');   // 获取严重程度2数据
r($bug->getDataOfBugsPerSeverityTest()) && p('3:name,value') && e('3,79');   // 获取严重程度3数据
r($bug->getDataOfBugsPerSeverityTest()) && p('4:name,value') && e('4,78');   // 获取严重程度4数据