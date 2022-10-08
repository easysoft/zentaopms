#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getTeamMembersPairs();
cid=1
pid=1

测试是否能拿到数据 >> admin

*/

$tutorial = new tutorialTest();

r($tutorial->getTeamMembersPairsTest()) && p('admin') && e('admin'); //测试是否能拿到数据