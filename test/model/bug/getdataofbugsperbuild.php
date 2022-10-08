#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerBuild();
cid=1
pid=1

获取主干bug数 >> 主干,291
获取bug数 >> 未设定,3
获取项目版本版本1bug数 >> 项目版本版本1,6

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerBuildTest()) && p('trunk:name,value') && e('主干,291');        // 获取主干bug数
r($bug->getDataOfBugsPerBuildTest()) && p('0:name,value')     && e('未设定,3');              // 获取bug数
r($bug->getDataOfBugsPerBuildTest()) && p('1:name,value')     && e('项目版本版本1,6'); // 获取项目版本版本1bug数