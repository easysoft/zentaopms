#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getExecutionModulePairs();
cid=1
pid=1

正常查询 >> 子目录200
正常查询统计 >> 4520

*/
global $tester;
$doc = $tester->loadModel('doc');

r($doc->getExecutionModulePairs())        && p('3920') && e('子目录200');//正常查询
r(count($doc->getExecutionModulePairs())) && p()       && e('4520');     //正常查询统计