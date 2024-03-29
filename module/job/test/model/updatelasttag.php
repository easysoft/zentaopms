#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->updateLastTag();
timeout=0
cid=1

- 查询id为1的job的版本库的last tag属性lastTag @testTag

*/

zdTable('job')->gen(5);

$job = new jobTest();
r($job->updateLastTagTest(1, 'testTag')) && p('lastTag') && e('testTag');  // 查询id为1的job的版本库的last tag