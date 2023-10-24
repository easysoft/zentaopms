#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->delete();
cid=1
pid=1

测试删除之后deleted值是否为1 >> 1

*/

$pipeline = new pipelineTest();

r($pipeline->deleteTest(1)) && p('deleted') && e('1'); //测试删除之后deleted值是否为1

