#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::checkHasContent;
cid=1
pid=1

*/
$project = $tester->loadModel('project');

r($project->checkHasContent(1)) && p() && e('1'); // 查找是否存在父级或者所属项目为1的数据，若存在则为true

r($project->checkHasContent(0)) && p() && e('1'); // 查找是否存在父级或者所属项目为0的数据，若存在则为ture
