#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/api.class.php';
su('admin');

/**

title=测试 apiModel->deleteRelease();
cid=1
pid=1

删除一个发布 >> 1

*/

$api = new apiTest();
r($api->deleteReleaseTest(1, 1)) && p('id') && e('1'); //删除一个发布
