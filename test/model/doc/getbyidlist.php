#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 docModel->getByIdList();
cid=1
pid=1



*/
global $tester;
$doc = $tester->loadModel('doc');

$docIDList = array('0', '117', '1', '217');

r($doc->getByIdList($docIDList))        && p('901:lib,content') && e('1,文档正文901');//正常查询文档库下的文档
r(count($doc->getByIdList($docIDList))) && p()                  && e('3');            //查询文档库下的文档统计