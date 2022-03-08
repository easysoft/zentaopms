#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getDefaultManagers();
cid=1
pid=1

敏捷查找访问人员 >> po1
瀑布查找访问人员 >> test21
看板查找访问人员 >> dev51

*/

$executionID = array('101', '131', '161');

$execution = new executionTest();
r($execution->getDefaultManagersTest($executionID[0])) && p('PO') && e('po1');    //敏捷查找访问人员
r($execution->getDefaultManagersTest($executionID[1])) && p('QD') && e('test21'); //瀑布查找访问人员
r($execution->getDefaultManagersTest($executionID[2])) && p('RD') && e('dev51');  //看板查找访问人员