#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->setTreePathTest();
cid=1
pid=1

子阶段设置path >> 41,131,,41,131,701,
子阶段设置path >> 11,11,,11,101,
子阶段设置path >> 41,41,,41,131,

*/

$executionIDList  = array('701', '101', '131');

$execution = new executionTest();
r($execution->setTreePathTest($executionIDList[0])) && p('701:project,parent,path') && e('41,131,,41,131,701,'); // 子阶段设置path
r($execution->setTreePathTest($executionIDList[1])) && p('101:project,parent,path') && e('11,11,,11,101,'); // 子阶段设置path
r($execution->setTreePathTest($executionIDList[2])) && p('131:project,parent,path') && e('41,41,,41,131,'); // 子阶段设置path