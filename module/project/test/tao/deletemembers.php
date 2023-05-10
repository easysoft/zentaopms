#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 projectTao::deleteMembers();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');

$project = new stdclass();
$members = array();

r($tester->project->deleteMembers(2, 1, $members)) && p() && e('1');
