#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::getModuleNames();
timeout=0
cid=1

- 获取zt_bug和zt_project对应的模块名。
 - 属性zt_bug @bug
 - 属性zt_project @project

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->getModuleNames(array('zt_bug', 'zt_project'))) && p('zt_bug;zt_project')  && e('bug,project');  //获取zt_bug和zt_project对应的模块名。
