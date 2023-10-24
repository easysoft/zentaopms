#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->deleteRelease();
cid=1
pid=1

删除一个发布 >> 1

*/

global $tester;
r($api->deleteReleaseTest(1, 1)) && p('id') && e('1'); //删除一个发布
